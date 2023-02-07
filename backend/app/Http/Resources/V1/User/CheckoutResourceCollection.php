<?php

namespace App\Http\Resources\V1\User;

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
                'cart'=>$item->cart->cart,
                'amount'=>number_format($item->amount),
                'status_fa'=>status($item->status),
                'status'=>$item->status,
                'created_at'=>dateToPersian($item->created_at)
            ];
        });
    }

    public function with($request)
    {
        return [
            'carts'=>Cart::where('status','yes')->get(),
            'contentTitle'=>config('menu.panel.userWallet.contentTitle')[1]
        ];
    }

}
