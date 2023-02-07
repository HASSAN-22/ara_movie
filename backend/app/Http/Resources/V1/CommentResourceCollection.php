<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use function App\Auxiliary\dateAgo;
use function App\Auxiliary\status;

class CommentResourceCollection extends ResourceCollection
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
                'name'=>$item->name,
                'email'=>$item->email,
                'comment'=>$item->comment,
                'status'=>status($item->status),
                'ago'=>dateAgo($item->created_at),
                'commentAnswers'=>$item->commentAnswers->map(function($answer){
                    return[
                        'id'=>$answer->id,
                        'comment_id'=>$answer->comment_id,
                        'name'=>$answer->name,
                        'email'=>$answer->email,
                        'answer'=>$answer->answer,
                        'position'=>$answer->position,
                        'margin'=>"mr-[.".($answer->position+3)."rem]",
                        'status'=>status($answer->status),
                        'ago'=>dateAgo($answer->created_at),
                    ];
                })
            ];
        });
    }

    public function with($request)
    {
        $user = auth()->user();
        $answerNotifications = $user->unreadNotifications()->where('type','App\Notifications\CommentAnswerNotify')->get();
        $commentNotifications = $user->unreadNotifications()->where('type','App\Notifications\CommentNotify')->get();
        return [
            'contentTitle'=>config('menu.panel.comment.contentTitle'),
            'answerNotify'=>$answerNotifications,
            'commentNotify'=>$commentNotifications,
        ];
    }
}
