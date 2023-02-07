<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryResourceCollection extends ResourceCollection
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
           return[
               'id'=>$item->id,
               'parent_id'=>$item->parent_id,
               'title'=>$item->title,
               'status'=>$item->status == 'yes' ? 'text-green-500' : 'text-red-500',
               'children'=>$item->children
           ];
        });
    }

    public function with($request)
    {
        return [
            'contentTitle'=>config('menu.panel.category.contentTitle')
        ];
    }
}
