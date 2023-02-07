<?php

namespace App\Http\Resources\V1\User;

use App\Models\BankPortal;
use App\Models\Cart;
use App\Models\Plan;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Cache;
use function App\Auxiliary\dateToPersian;

class VipTransactionResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id'=>$item->id,
                'cart'=>$item->cart,
                'plan'=>$item->plan,
                'type'=>$item->type == 'wallet' ? 'کیف پول' : 'اینترنتی',
                'price'=>number_format($item->price),
                'discount'=>$item->discount,
                'transaction_id'=>$item->transaction_id,
                'created_at'=>dateToPersian($item->created_at),
                'expire'=>dateToPersian($item->expire),
                'isExpire'=>$item->expire > $item->created_at
            ];
        });
    }

    public function with($request)
    {
        $key = 'message_vip_'.auth()->Id();
        $message = null;
        if(Cache::has($key)){
            $message = Cache::get($key);
            Cache::forget($key);
        }
        return [
            'carts'=>Cart::where('status','yes')->get(),
            'plans'=>Plan::get(),
            'bankPortals' => BankPortal::where('status','yes')->get(),
            'message'=>$message,
            'contentTitle'=>config('menu.panel.userVip.contentTitle')
        ];
    }

}
