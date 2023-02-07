<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use function App\Auxiliary\deleteNotification;

class ReportLinkResourceCollection extends ResourceCollection
{
    private $notifications;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = auth()->user();
        $this->notifications = $user->unreadNotifications()->where('type','App\Notifications\ReportLinkNotify')->get();
        return $this->collection->map(function($item){
            deleteNotification('App\Notifications\ReportLinkNotify',$item->id,'report');
            return [
                'id'=>$item->id,
                'title'=>$item->movie->title,
                'movieLinkTitle'=>$item->movieLink->title,
                'linkTitle'=>$item->link->title,
                'link'=>config('app.front_url').'detail/'.$item->movie->slug
            ];
        });
    }

    public function with($request)
    {
        return [
            'contentTitle'=>config('menu.panel.video.contentTitle')[3],
            'notifications'=>$this->notifications,
        ];
    }
}
