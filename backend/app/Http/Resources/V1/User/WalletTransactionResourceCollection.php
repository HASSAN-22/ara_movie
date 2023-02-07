<?php

namespace App\Http\Resources\V1\User;

use App\Models\BankPortal;
use App\Models\Cart;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Cache;
use function App\Auxiliary\dateToPersian;

class WalletTransactionResourceCollection extends ResourceCollection
{
    private int $sumAmount = 0;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function($item){
            $this->sumAmount += $item->amount;
            return[
                'id'=>$item->id,
                'cart'=>$item->cart,
                'amount'=>number_format($item->amount),
                'transaction_id'=>$item->transaction_id,
                'created_at'=>dateToPersian($item->created_at)
            ];
        });
    }

    public function with($request)
    {
        $user = auth()->user();
        $key = 'message_'.$user->id;
        $message = null;
        if(Cache::has($key)){
            $message = Cache::get($key);
            Cache::forget($key);
        }
        return [
            'carts'=>Cart::where('status','yes')->get(),
            'bankPortals' => BankPortal::where('status','yes')->get(),
            'message'=>$message,
            'wallet'=> number_format($user->wallet->amount ?? 0),
            'amount'=>number_format($this->sumAmount),
            'contentTitle'=>config('menu.panel.userWallet.contentTitle')[0]
        ];
    }

}
