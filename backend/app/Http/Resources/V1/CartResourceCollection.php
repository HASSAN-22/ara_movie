<?php

namespace App\Http\Resources\V1;

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
            $color = 'text-green-500';
            switch ($item->status){
                case 'no' : $color = 'text-red-500';break;
                case 'pending' : $color = 'text-blue-500';break;
            }
            return [
                'id'=>$item->id,
                'user'=>$item->user->name,
                'mobile'=>$item->user->mobile,
                'cart'=>$item->cart,
                'shaba'=>$item->shaba,
                'status'=>status($item->status),
                'color'=>$color,
                'bank'=> $item->bank_name
            ];
        });
    }

    public function with($request)
    {
        $notifications = auth()->user()->unreadNotifications()->where('type','App\Notifications\CartNotify')->get();
        return [
            'contentTitle'=>config('menu.panel.bank.contentTitle')[0],
            'notifications'=>$notifications
        ];
    }
}
