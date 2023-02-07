<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Resources\Json\ResourceCollection;
use function App\Auxiliary\status;

class CartResourceCollection extends ResourceCollection
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
                'cart'=>$item->cart,
                'shaba'=>$item->shaba,
                'status'=>status($item->status),
                'bank'=> $item->bank_name
            ];
        });
    }
    public function with($request)
    {
        return [
            'contentTitle'=>config('menu.panel.userCart.contentTitle'),
        ];
    }
}
