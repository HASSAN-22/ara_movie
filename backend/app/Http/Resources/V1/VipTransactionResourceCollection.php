<?php

namespace App\Http\Resources\V1;

use App\Models\BankPortal;
use App\Models\Cart;
use App\Models\Plan;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Cache;
use function App\Auxiliary\dateToPersian;
use function App\Auxiliary\discount;

class VipTransactionResourceCollection extends ResourceCollection
{
    private int $amount = 0;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            $this->amount += discount($item->price,$item->discount);
            return [
                'id'=>$item->id,
                'user'=>$item->user->name,
                'mobile'=>$item->user->mobile,
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
        return [
            'amount'=>number_format($this->amount),
            'contentTitle'=>config('menu.panel.vip.contentTitle')
        ];
    }

}
