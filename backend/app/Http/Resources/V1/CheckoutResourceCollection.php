<?php

namespace App\Http\Resources\V1;

use App\Models\Cart;
use Illuminate\Http\Resources\Json\ResourceCollection;
use function App\Auxiliary\dateToPersian;
use function App\Auxiliary\status;

class CheckoutResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function($item){
            return [
                'id'=>$item->id,
                'user'=>$item->user->name,
                'mobile'=>$item->user->mobile,
                'cart'=>$item->cart->cart,
                'shaba'=>$item->cart->shaba,
                'amount'=>number_format($item->amount),
                'status'=>status($item->status),
                'created_at'=>dateToPersian($item->created_at)
            ];
        });
    }

    public function with($request)
    {
        $notifications = auth()->user()->unreadNotifications()->where('type','App\Notifications\CheckoutNotify')->get();
        return [
            'contentTitle'=>config('menu.panel.bank.contentTitle')[3],
            'notifications'=>$notifications
        ];
    }
}
