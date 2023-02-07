<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CommentRequest;
use App\Http\Resources\V1\FrontGetMovieResourceCollection;
use App\Models\Category;
use App\Models\Comment;
use App\Models\CommentAnswer;
use App\Models\ConfigSite;
use App\Models\Movie;
use App\Models\Page;
use App\Models\Plan;
use App\Models\ReportLink;
use App\Models\Slider;
use App\Models\WatchList;
use Illuminate\Http\Request;
use function App\Auxiliary\sendNotification;

class FrontController extends Controller
{
    private $siteName;

    public function __construct()
    {
        $this->siteName = config('app.name');

    }

    public function index(){
        $config = ConfigSite::first();
        $sidebarText = config('menu.front');
        $sliders = Slider::with(['movie'])->get();
        $categories = Category::with('children')->where('status','yes')->get();
        $lastSerial = $this->movieQuery('serial')->where(['soon'=>'no','play_status'=>'yes'])->latest()
            ->with(['latestMovieLink'=>fn($q)=>$q->select('id','movie_id','caption_movie')])->get(['id','imdb_number','image','slug','title'])->take(10);
        $comingSoon = $this->movieQuery()->where('soon','yes')->get(['id','imdb','title','image','slug','published_at']);
        $pages = Page::where('status','yes')->whereNotIn('id',[1,2])->get();
        $allGenres =  Movie::get(['genre'])->pluck('genre')->toArray();
        $genres = [];
        foreach ($allGenres as $genre){
            foreach (explode('،',$genre) as $item){
                $genres[] = trim($item);
            }
        }
        $genres = array_count_values($genres);
        $data = ['categories'=>$categories,'sliders'=>$sliders,'pages'=>$pages,'config'=>$config,'sidebarText'=>$sidebarText,'lastSerial'=>$lastSerial,'comingSoon'=>$comingSoon,'genres'=>$genres,'siteName'=>$this->siteName];
        return response(['status'=>'success','data'=>$data]);
    }

    public function genre($genre,$type,$take=1){
        $genre = $genre == 'all' ? '' : $genre;
        $movies = Movie::where(['type'=>$type,'status'=>'yes','soon'=>'no'])->where('genre','like',"%$genre%")->get()->take($take);
        return response(['status'=>'success','data'=>$movies]);
    }

    public function getMovies(Request $request){
        $value = !is_null($request->v) ? trim($request->v) : null;
        $type = $request->type;
        $query = Movie::where(['status'=>'yes','soon'=>'no'])->with(['latestMovieLink'=>fn($q)=>$q->select('id','movie_id','caption_movie')]);
        $query = !is_null($type) ? $query->where('type',$type) : $query;
        if(!empty($request->t)){
            $user = auth('sanctum')->user();
            if($user){
                $query = $query->with(['watchLists'=>fn($q)=>$q->where('user_id',$user->id)]);
            }
        }
        if(!empty($value)){
            $query = $query->where(function($query)use($value){
                return $query->where('genre','like',"%$value%")->orWhere('published_at','like',"%$value%")
                ->orWhere('actor','like',"%$value%");
            });
        }
        $movies = $query->orderBy('updated_at')->latest()->paginate(10);
        return new FrontGetMovieResourceCollection($movies);
    }

    public function listMovies(Request $request){
        $query = Movie::where('status','yes');
        switch($request->slug){
            case 'coming-soon' : $query = $query->where('soon','yes'); break;
            case 'update-serise' : $query = $query->where(['soon'=>'no','play_status'=>'yes']); break;
            default:$query = $query->where('soon','no')->whereRelation('category','slug',$request->slug);break;
        }
        $query = $query->with(['latestMovieLink'=>fn($q)=>$q->select('id','movie_id','caption_movie')]);
        if(!empty($request->t)){
            $user = auth('sanctum')->user();
            if($user){
                $query = $query->with(['watchLists'=>fn($q)=>$q->where('user_id',$user->id)]);
            }
        }
        $movies = $query->orderBy('updated_at')->latest()->paginate(10);
        return new FrontGetMovieResourceCollection($movies);

    }

    public function watchList(Movie $movie){
        $watch = auth()->user()->watchLists->where('movie_id',$movie->id)->first();
        $data = 'delete';
        if($watch){
            $watch->delete();
        }else{
            $data = 'create';
            $watch = WatchList::create(['user_id'=>auth()->Id(), 'movie_id'=>$movie->id]);
        }
        return $watch ? response(['status'=>'success','data'=>$data]) : response(['status','error'],500);
    }

    public function movieDetail($slug){
        $user = auth('sanctum')->user();
        $vip = false;
        $isAdmin = false;
        $column = is_numeric($slug) ? 'short_link' : 'slug';
        $query = Movie::where(['status'=>'yes',$column=>$slug])->with(['likes','movieLinks'=>fn($q)=>$q->with(['links','trailers','screenShot']),'movieTags','comments'=>fn($q)=>$q->where('status','yes')->with(['likes','commentAnswers'=>fn($q)=>$q->where('status','yes')->with('likes')])]);
        if($user){
            $isAdmin = $user->Admin();
            $vip = !is_null($user->vipTransactions->where('expire','>',now())->first());
            $query = $query->with(['watchLists'=>fn($q)=>$q->where('user_id',$user->id)]);
        }
        $movie = $query->first();
        $likes = $movie->likes()->where('type','like')->get()->count();
        $dislikes = $movie->likes()->where('type','dislike')->get()->count();
        $relatedMovies = $movie->category->movies()->where(['status'=>'yes','soon'=>'no'])->inRandomOrder()->limit(10)->get();
        $movie['like']=$likes;
        $movie['dislike']=$dislikes;
        $movie['relatedMovies']=$relatedMovies;
        $movie['vip']=$vip;
        $movie['isAdmin']=$isAdmin;
        $movie['siteName']=$this->siteName;
        return response(['status'=>'success','data'=>$movie]);
    }

    public function comment(CommentRequest $request, Movie $movie){
        if($request->type == 'answer'){
            $answer = CommentAnswer::create([
                'parent_id'=>$request->answer_id,
                'name'=>$request->name,
                'email'=>$request->email,
                'comment_id'=>$request->comment_id,
                'answer'=>$request->comment,
                'status'=>'pending',
            ]);
            sendNotification($answer->id,'App\Notifications\CommentAnswerNotify', 'admin');
        }else{
            $comment = Comment::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'comment'=>$request->comment,
                'status'=>'pending',
                'commentable_type'=>'App\Models\Movie',
                'commentable_id'=>$movie->id,
            ]);
            sendNotification($comment->id,'App\Notifications\CommentNotify', 'admin');
        }
        $comments = $movie->comments()->where('status','yes')->with(['likes','commentAnswers'=>fn($q)=>$q->where('status','yes')->with('likes')])->get();
        return response(['status'=>'success','data'=>$comments]);
    }

    public function likeAndDisLikeComment(Request $request){
        $id = $request->id;
        $comment = null;
        if($request->action == 'answer'){
            $post = CommentAnswer::where('id',$id)->with('comment')->first();
            $comment = $post->comment;
        }else{
            $post = $comment = Comment::find($id);
        }
        $this->likeAndDislike($post->likes(), $request);
        $comments = $comment->commentable->comments()->where('status','yes')->with(['likes','commentAnswers'=>fn($q)=>$q->where('status','yes')->with('likes')])->get();
        return response(['status'=>'success','data'=>$comments]);
    }

    public function likeAndDisLikeMovie(Request $request,Movie $movie){
        $this->likeAndDislike($movie->likes(), $request);
        $likes = $movie->likes()->where('type','like')->get()->count();
        $dislikes = $movie->likes()->where('type','dislike')->get()->count();
        return response(['status'=>'success','data'=>['like'=>$likes,'dislike'=>$dislikes]]);
    }

    public function reportLink(Request $request){
        $request->validate(['description'=>['required','string','max:1000','min:5']]);
        $report = ReportLink::create([
            'movie_id'=>$request->movie_id,
            'movie_link_id'=>$request->movie_link_id,
            'link_id'=>$request->link_id,
            'description'=>$request->description
        ]);
        if($report){
            sendNotification($report->id,'App\Notifications\ReportLinkNotify', 'admin');
            return response(['status'=>'success']);
        }
        return response(['status'=>'error'],500);
    }

    public function search(Request $request){
        $text = trim($request->text);
        $movies = Movie::where(['status'=>'yes','soon'=>'no'])->where('title','like',"%$text%")->orWhere('fa_title','like',"%$text%")->get(['id','title','fa_title','genre','description','imdb','image','slug']);
        return response(['status'=>'success','data'=>$movies]);
    }

    public function showPage($title){
        $page = Page::where('title',$title)->first();
        return response(['status'=>'success','data'=>['page'=>$page,'siteName'=>$this->siteName]]);

    }

    public function getConfig(){
        return response(['status'=>'success','data'=>ConfigSite::first()]);
    }


    public function getPlan(){
        $plans = Plan::with(['discounts'=>fn($q)=>$q->where('expire','>',now())->orderBy('created_at','desc')])->get();
        $title = config('menu.front.vip.title');
        return response(['status'=>'success','data'=>['plans'=>$plans,'title'=>$title,'siteName'=>$this->siteName]]);
    }


    private function movieQuery(string $type = 'movie'){
        return Movie::where(['status'=>'yes','type'=>$type]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Relations\MorphMany $likes
     * @param Request $request
     */
    private function likeAndDislike(\Illuminate\Database\Eloquent\Relations\MorphMany $likes, Request $request): void
    {
        $ip = \request()->ip();
        $like = $likes->firstWhere('ip', $ip);
        if ($request->type == 'like') {
            if (is_null($like)) {
                $likes->create(['ip' => $ip, 'type' => 'like']);
            } else {
                $like->type = 'like';
                $like->save();
            }
        }
        if ($request->type == 'dislike') {
            if (is_null($like)) {
                $likes->create(['ip' => $ip, 'type' => 'dislike']);
            } else {
                $like->type = 'dislike';
                $like->save();
            }
        }
    }
}
