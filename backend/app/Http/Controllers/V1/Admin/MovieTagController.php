<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\MovieTagRequest;
use App\Http\Resources\V1\MovieTagResourceCollection;
use App\Models\MovieTag;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tag;

class MovieTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',MovieTag::class);
        $tags = MovieTag::groupBy('movie_id')->latest()->with(['movie'=>fn($q)=>$q->select('id','title')])->paginate();
        return new MovieTagResourceCollection($tags);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MovieTagRequest $request)
    {
        $this->authorize('create',MovieTag::class);

        $tag = MovieTag::insert($this->tags($request));
        return $tag ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MovieTag  $movieTag
     * @return \Illuminate\Http\Response
     */
    public function show(MovieTag $movieTag)
    {
        $this->authorize('view',$movieTag);
        $movie =  $movieTag->movie->with(['movieTags'])->first();
        return response(['status'=>'success','data'=>[$movie,$movieTag->id]]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MovieTag  $movieTag
     * @return \Illuminate\Http\Response
     */
    public function update(MovieTagRequest $request, MovieTag $movieTag)
    {
        $this->authorize('update',$movieTag);
        $movieTag->movie->movieTags()->delete();
        $tag = MovieTag::insert($this->tags($request));
        return $tag ? response(['status'=>'success']) : response(['status'=>'error'],500);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MovieTag  $movieTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(MovieTag $movieTag)
    {
        $this->authorize('delete',$movieTag);
        return $movieTag->movie->movieTags()->delete() ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    private function tags($request){
        $tags = [];
        foreach ($request->titles as $key=>$title){
            $tags[]=[
                'movie_id'=>$request->movie_id,
                'title' => $title,
                'link' => $request->links[$key],
                'created_at'=>now(),
                'updated_at'=>now(),
            ];
        }
        return $tags;
    }
}
