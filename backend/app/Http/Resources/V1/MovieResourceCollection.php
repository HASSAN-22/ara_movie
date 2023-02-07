<?php

namespace App\Http\Resources\V1;

use App\Models\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;

use function App\Auxiliary\dateToPersian;

class MovieResourceCollection extends ResourceCollection
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
                'category'=>$item->category->title,
                'title'=>$item->title,
                'type'=>$item->type == 'serial' ? 'سریال' : 'ویدیو',
                'image'=>$item->image,
                'quality'=>$item->quality,
                'genre'=>$item->genre,
                'imdb'=>$item->imdb,
                'subtitle'=>$item->subtitle == 'yes' ? 'بله' : 'خیر',
                'dubbed'=>$item->dubbed == 'yes' ? 'بله' : 'خیر',
                'status'=>$item->status == 'yes' ? 'بله' : 'خیر',
                'status_comment'=>$item->status_comment == 'yes' ? 'بله' : 'خیر',
                'soon'=>$item->soon == 'yes' ? 'بله' : 'خیر',
                'created_at'=>dateToPersian($item->created_at)
            ];
        });
    }

    public function with($request)
    {
        return [
            'categories'=>Category::get(['id','title']),
            'contentTitle'=>config('menu.panel.video.contentTitle')[0]
        ];
    }

}
