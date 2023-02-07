<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PlanResourceCollection extends ResourceCollection
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
           return[
               'id'=>$item->id,
               'title'=>$item->title,
               'price'=>number_format($item->price),
               'days'=>$item->days,
               'image'=>$item->image,
               'description'=>$item->description
           ];
        });
    }
    public function with($request)
    {
        return [
            'contentTitle'=>config('menu.panel.plan.contentTitle')
        ];
    }
}
