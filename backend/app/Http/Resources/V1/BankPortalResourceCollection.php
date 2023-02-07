<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use function App\Auxiliary\active;

class BankPortalResourceCollection extends ResourceCollection
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
                'bank_name'=>$item->bank_name,
                'status'=>active($item->status)
            ];
        });
    }

    public function with($request)
    {
        return [
            'contentTitle'=>config('menu.panel.bank.contentTitle')[1]
        ];
    }
}
