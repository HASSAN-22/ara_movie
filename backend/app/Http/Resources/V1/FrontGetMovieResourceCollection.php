<?php

namespace App\Http\Resources\V1;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FrontGetMovieResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function with($request){
        return [
            'siteName'=>config('app.name')
        ];
    }
}
