<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WatchListResourceCollection extends ResourceCollection
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
                'title'=>$item->movie->title,
                'imdb'=>$item->movie->imdb,
                'image'=>$item->movie->image,
            ];
        });
    }

    public function with($request)
    {
        return [
            'contentTitle'=>config('menu.panel.watchList.contentTitle')
        ];
    }
}
