<?php

namespace App\Http\Resources\V1;

use App\Models\Movie;
use Illuminate\Http\Resources\Json\ResourceCollection;
use function App\Auxiliary\dateToPersian;

class MovieLinkResourceCollection extends ResourceCollection
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
                'movie'=>$item->movie->title,
                'title'=>$item->title,
                'vip'=>$item->vip == 'yes' ? 'بله' : 'خیر',
                'created_at'=>dateToPersian($item->created_at)
            ];
        });
    }

    public function with($request)
    {
        return [
          'movies'=>Movie::get(['id','title']),
            'contentTitle'=>config('menu.panel.video.contentTitle')[1]
        ];
    }

}
