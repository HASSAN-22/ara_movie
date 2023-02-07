<?php

namespace App\Http\Controllers\V1;

use App\Auxiliary\Services\FixSearch;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CartResourceCollection;
use App\Http\Resources\V1\CommentResourceCollection;
use App\Http\Resources\V1\DiscountResourceCollection;
use App\Http\Resources\V1\MovieLinkResourceCollection;
use App\Http\Resources\V1\MovieResourceCollection;
use App\Http\Resources\V1\MovieTagResourceCollection;
use App\Http\Resources\V1\PageResourceCollection;
use App\Http\Resources\V1\User\CheckoutResourceCollection;
use App\Http\Resources\V1\CheckoutResourceCollection as adminCheckout;
use App\Http\Resources\V1\User\VipTransactionResourceCollection;
use App\Http\Resources\V1\VipTransactionResourceCollection as vipTransactionResource;
use App\Http\Resources\V1\User\WalletTransactionResourceCollection;
use App\Http\Resources\V1\User\WatchListResourceCollection;
use App\Http\Resources\V1\WalletTransactionResourceCollection as adminWalletTransaction;
use App\Http\Resources\V1\UserResourceCollection;
use App\Models\Cart;
use App\Models\Checkout;
use App\Models\Comment;
use App\Models\Discount;
use App\Models\Link;
use App\Models\Movie;
use App\Models\MovieLink;
use App\Models\MovieTag;
use App\Models\Page;
use App\Models\User;
use App\Models\VipTransaction;
use App\Models\WalletTransaction;
use App\Models\WatchList;
use Illuminate\Http\Request;
use function App\Auxiliary\dateToEnglish;

class SearchController extends Controller
{
    private $search;
    public function __construct()
    {
        $this->search = new FixSearch();
    }


    public function userSearch(Request $request){
        $this->authorize('viewAny',User::class);
        $search = $this->searchText($request->search)->fixAccess()->fixStatus()->get();
        $query = User::orWhere('name','like',"%$search%")->orWhere('mobile','like',"%$search%")->orWhere('email','like',"%$search%")
            ->orWhere('access',"$search")->orWhere('status',"$search");
        $users = $this->queryDate($search,$query)->get();
        return new UserResourceCollection($users);
    }

    public function movieSearch(Request $request){
        $this->authorize('viewAny',User::class);
        $search = $this->searchText($request->search)->fixYesAndNo()->fixType()->get();
        $callbackUser = fn($q)=>$q->select('id','title'); // short function for get title of categories
        $movies = Movie::where(function ($query)use($search){
            $query = $query->orWhere('title','like',"%$search%")->orWhere('fa_title','like',"%$search%")->orWhere('dubbed',"$search")
                ->orWhere('subtitle',"$search")->orWhere('vip',"$search")->orWhere('status_comment',"$search")
                ->orWhere('status',"$search")->orWhere('soon',"$search")->orWhere('good_movie',"$search")->orWhere('type',"$search");
            return $this->queryDate($search,$query);
        })->orWhereRelation('category','title',$search)->with(['category'=>$callbackUser])->get();
        return new MovieResourceCollection($movies);
    }

    public function movieLinkSearch(Request $request){
        $this->authorize('viewAny',MovieLink::class);
        $search = $this->searchText($request->search)->fixType()->get();
        $links = MovieLink::where(function ($q)use($search){
            $query = $q->orWhere('title','like',"%$search%");
            return $this->queryDate($search,$query);
        })->orWhereRelation('movie','title',$search)->with(['movie'=>$this->movieCallback()])->get();
        return new MovieLinkResourceCollection($links);
    }

    public function movieTagSearch(Request $request){
        $this->authorize('viewAny',MovieTag::class);
        $search = $this->searchText($request->search)->get();
        $tags = MovieTag::groupBy('movie_id')->where(function ($q)use($search){
            return $q->orWhere('title','like',"%$search%")->orWhere('link','like',"%$search%");
        })->orWhereRelation('movie','title',$search)->with(['movie'=>$this->movieCallback()])->get();
        return new MovieTagResourceCollection($tags);
    }

    public function discountSearch(Request $request){
        $this->authorize('viewAny',Discount::class);
        $search = $this->searchText($request->search)->fixPercent()->get();
        $discounts = Discount::where(function($q)use($search){
            $query = $q->orWhere('title','like',"%$search%")->orWhere('discount','like',"%$search%")
                ->orWhere('code','like',"%$search%");
            $query = $this->queryDate($search,$query,'expire');
            return $this->queryDate($search,$query);

        })->orWhereRelation('plan','title',$search)->with(['plan'=>fn($q)=>$q->select('id','title')])->get();
        return new DiscountResourceCollection($discounts);
    }

    public function cartSearch(Request $request){
        $this->authorize('viewAny',Cart::class);
        $search = $this->searchText($request->search)->fixStatus()->fixShaba()->get();
        $carts = Cart::where(function($q)use($search){
           return $q->orWhere('bank_name','like',"%$search%")
               ->orWhere('cart','like',"%$search%")
               ->orWhere('shaba','like',"%$search%")->orWhere('status',$search);
        })->orWhereRelation('user','name',$search)->orWhereRelation('user','mobile',$search)->with(['user'=>fn($q)=>$q->select('id','name','mobile')])->get();
        return new CartResourceCollection($carts);
    }

    public function watchList(Request $request){
        $this->authorize('viewAny',WatchList::class);
        $search = $this->searchText($request->search)->get();
        $watchLists = auth()->user()->watchLists()->whereHas('movie',function($q)use($search){
            return $q->where('title','like',"%$search%")->orWhere('imdb_number',"%$search%")
            ->orWhere('imdb','like',"%$search%");
        })->with(['movie'=>fn($q)=>$q->select('title','image','imdb','id')])->get();
        return new WatchListResourceCollection($watchLists);
    }

    public function walletTransactionSearch(Request $request){
        $ability = 'viewAny'.trim(!$this->isAdmin() ? 'User' : '');
        $this->authorize($ability,WalletTransaction::class);
        $search = $this->searchText($request->search)->get();
        $query = WalletTransaction::where(function ($q)use($search){
            $q->where(function ($q)use($search){
                $query = $q->orWhere('amount','like',"%$search%")->orWhere('transaction_id','like',"%$search%")
                    ->orWhere('cart','like',"%$search%");
                return $this->queryDate($search,$query);
            });
        });
        $transactions = $this->filterUser($query, $search);
        if(!$this->isAdmin()){
            return new WalletTransactionResourceCollection($transactions);
        }else{
            return new adminWalletTransaction($transactions);
        }

    }

    public function checkoutSearch(Request $request){
        $ability = 'viewAny'.trim(!$this->isAdmin() ? 'User' : '');
        $this->authorize($ability,Checkout::class);
        $search = $this->searchText($request->search)->fixPrice()->fixStatus()->fixShaba()->get();
        $query = Checkout::where(function ($q)use($search){
            $q->where(function ($q)use($search){
                $query = $q->orWhere('amount','like',"%$search%")->orWhere('status',$search);
                return $this->queryDate($search,$query);
            })->orWhereRelation('cart','cart',$search)->orWhereRelation('cart','shaba',$search)->with('cart');
        });
        $checkouts = $this->filterUser($query, $search);
        if(!$this->isAdmin()){
            return new CheckoutResourceCollection($checkouts);
        }else{
            return new adminCheckout($checkouts);
        }

    }

    public function vipSearch(Request $request)
    {
        $ability = 'viewAny' . trim(!$this->isAdmin() ? 'User' : '');
        $this->authorize($ability, VipTransaction::class);
        $search = $this->searchText($request->search)->fixPrice()->fixStatus()->fixShaba()->fixPercent()->payType()->get();
        $query = VipTransaction::where(function ($q) use ($search) {
            $query = $q->orWhere('price', 'like', "%$search%")->orWhere('plan', 'like', "%$search%")
                ->orWhere('cart', 'like', "%$search%")->orWhere('discount', 'like', "%$search%")
                ->orWhere('transaction_id', 'like', "%$search%");
            $query = $this->queryDate($search, $query, 'expire');
            return $this->queryDate($search, $query);
        });
        $transactions = $this->filterUser($query, $search);
        if (!$this->isAdmin()) {
            return new VipTransactionResourceCollection($transactions);
        } else {
            return new vipTransactionResource($transactions);
        }
    }

    public function commentSearch(Request $request){
        $this->authorize('viewAny',Comment::class);
        $search = $this->searchText($request->search)->fixStatus()->get();
        $comments = Comment::where(function($q)use($search){
            return $q->orWhere('name','like',"%$search%")->orWhere('email','like',"%$search%")->orWhere('status','like',"%$search%")
               ->orWhere('comment','like',"%$search%");
        })->orWhereRelation('commentAnswers','answer',$search)->orWhereRelation('commentAnswers','name',$search)
            ->orWhereRelation('commentAnswers','email',$search)->get();
        return new CommentResourceCollection($comments);

    }

    public function pageSearch(Request $request)
    {
        $this->authorize('viewAny', Page::class);
        $search = $this->searchText($request->search)->fixStatus()->get();
        $pages = Page::orWhere('title','like',"%$search%")->orWhere('description','like',"%$search%")->orWhere('status',"%$search%")->get();
        return new PageResourceCollection($pages);
    }
    private function queryDate(string $search, $query,string $column='created_at')
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $search)) {
            $query = $query->orWhere($column, 'like', "%" . dateToEnglish($search) . "%");
        }
        return $query;
    }

    /**
     * @param string|null $search
     * @return FixSearch
     */
    private function searchText(?string $search): FixSearch
    {
        return $this->search->setText($search);
    }

    /**
     * @return \Closure
     */
    private function movieCallback(): \Closure
    {
        return fn($q) => $q->select('id', 'title');
    }

    private function isAdmin(){
        return auth()->user()->access == 'admin';
    }

    /**
     * @param $query
     * @param string $search
     * @return mixed
     */
    private function filterUser($query, string $search)
    {
        return !$this->isAdmin() ?
            $query->whereRelation('user', 'user_id', auth()->Id())->get() :
            $query->orWhereRelation('user', 'name', $search)->orWhereRelation('user', 'mobile', $search)->with(['user' => fn($q) => $q->select('id', 'name', 'mobile')])->get();
    }
}
