<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

use function App\Auxiliary\access;
use function App\Auxiliary\active;
use function App\Auxiliary\dateToPersian;

class UserResourceCollection extends ResourceCollection
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
                'name'=>$item->name,
                'access'=>access($item->access),
                'is_admin'=>$item->access === 'user' ? false : true,
                'email'=>$item->email,
                'mobile'=>$item->mobile,
                'status'=>active($item->status),
                'avatar'=>$item->avatar,
                'created_at'=>dateToPersian($item->created_at)
            ];
         });
    }
    public function with($request)
    {
        return [
            'contentTitle'=>config('menu.panel.user.contentTitle'),
            'menu' => config('menu.panel')
        ];
    }
}
