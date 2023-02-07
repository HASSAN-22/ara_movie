<?php

namespace App\Http\Resources\V1;

use App\Models\Plan;
use Illuminate\Http\Resources\Json\ResourceCollection;
use function App\Auxiliary\dateToPersian;

class DiscountResourceCollection extends ResourceCollection
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
                'title'=>$item->title,
                'plan'=>$item->plan->title,
                'discount'=>$item->discount,
                'code'=>$item->code,
                'created_at'=>dateToPersian($item->created_at),
                'expire'=>dateToPersian($item->expire)
            ];
        });
    }

    public function with($request)
    {
        return [
            'plans'=>Plan::get(['id','title']),
            'contentTitle'=>config('menu.panel.discount.contentTitle')
        ];
    }
}
