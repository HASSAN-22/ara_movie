<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\ConfigSite;
use App\Models\Movie;
use App\Models\User;
use App\Models\VipTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function App\Auxiliary\getChartData;

class BackendController extends Controller
{

    public function menu(){
        $panel = config('menu.panel');
        $config = ConfigSite::first();
        $user = auth()->user();
        $wallet = $user->wallet;
        $walletAmount = !is_null($wallet) ? number_format($user->wallet->amount) : 0;
        $cartNotifyCount = $checkoutCount = $commentCount = $reportLinkCount = 0;
        if($user->access == 'admin'){
            $cartNotifyCount = $user->unreadNotifications()->where('type','App\Notifications\CartNotify')->count();
            $checkoutCount = $user->unreadNotifications()->where('type','App\Notifications\CheckoutNotify')->count();
            $commentCount= $user->unreadNotifications()->where('type','App\Notifications\CommentNotify')->orWhere('type','App\Notifications\CommentAnswerNotify')->count();
            $reportLinkCount = $user->unreadNotifications()->where('type','App\Notifications\ReportLinkNotify')->count();;
        }

        return response(['status'=>'success','data'=>[
            'menu'=>$panel,
            'walletAmount'=>$walletAmount,
            'config'=>$config,
            'cartCount'=>$cartNotifyCount,
            'reportLinkCount'=>$reportLinkCount,
            'checkoutCount'=>$checkoutCount,
            'commentCount'=>$commentCount,
            'isActive'=>$user->status == 'yes'
            ]
        ]);
    }

    public function dashboard(){
        $user = auth()->user();
        $userChart = getChartData(new User());
        $vipTransactionChart = getChartData(new VipTransaction());
        $movieChart = getChartData(new Movie());

        $query = VipTransaction::with(['user'=>fn($q)=>$q->select('id','name','mobile')])->latest();
        $vipTransactions = $user->access == 'admin' ? $query->get()->take(5) : $query->where('user_id',$user->id)->get()->take(5) ;
        return response(['status'=>'success','data'=>[$userChart,$vipTransactionChart,$movieChart,$vipTransactions]]);
    }
}
