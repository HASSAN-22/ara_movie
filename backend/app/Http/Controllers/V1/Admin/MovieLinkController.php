<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\MovieLinkRequest;
use App\Http\Resources\V1\MovieLinkResourceCollection;
use App\Models\Link;
use App\Models\MovieLink;
use App\Models\Trailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $this->authorize('viewAny',MovieLink::class);
        $movieLinks = MovieLink::with([ 'movie'=>fn($q)=>$q->select('id','title') ])->latest()->paginate();
        return new MovieLinkResourceCollection($movieLinks);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MovieLinkRequest $request)
    {
        $this->authorize('create',MovieLink::class);
        DB::beginTransaction();
        try {
            $movieLink = MovieLink::create([
               'movie_id'=>$request->movie_id,
               'title'=>$request->title,
               'caption_movie'=>$request->caption_movie,
               'vip'=>$request->vip,

            ]);
            if(!empty($request->screenShot)){
                $movieLink->screenShot()->create(['screen_shot'=>$request->screenShot]);
            }
            if(count($request->trailers)){
                Trailer::insert($this->trailers($movieLink->id,$request));
            }
            Link::insert($this->links($movieLink->id,$request));
            DB::commit();
            return response(['status'=>'success']);

        }catch (\Exception $e){
            DB::rollBack();
            return response(['status'=>'error'],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MovieLink  $movieLink
     * @return \Illuminate\Http\Response
     */
    public function show(MovieLink $movieLink)
    {
        $this->authorize('view',$movieLink);
        $moveCallBack = fn($q)=>$q->select('id','title');
        $movieLink = $movieLink->where('id',$movieLink->id) ->with(['movie'=>$moveCallBack,'screenShot','links','trailers'])->first();
        return response(['status'=>'success','data'=>$movieLink]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MovieLink  $movieLink
     * @return \Illuminate\Http\Response
     */
    public function update(MovieLinkRequest $request, MovieLink $movieLink)
    {
        $this->authorize('update',$movieLink);
        DB::beginTransaction();
        try {
            $movieLink->movie_id = $request->movie_id;
            $movieLink->title = $request->title;
            $movieLink->caption_movie = $request->caption_movie;
            $movieLink->vip = $request->vip;
            $movieLink->save();
            if(!empty($request->screenShot)){
                $movieLink->screenShot()->updateOrCreate(['screen_shot'=>$request->screenShot]);
            }
            if(count($request->trailers)){
                $movieLink->trailers()->delete();
                Trailer::insert($this->trailers($movieLink->id,$request));
            }
            $movieLink->links()->delete();
            Link::insert($this->links($movieLink->id,$request));
            DB::commit();
            return response(['status'=>'success']);

        }catch (\Exception $e){
            DB::rollBack();
            return response(['status'=>'error','e'=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MovieLink  $movieLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(MovieLink $movieLink)
    {
        $this->authorize('delete',$movieLink);
        return $movieLink->delete() ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }


    private function links($movieLinkId,$request){
        $links = [];
        foreach ($request->titles as $key=>$title) {
            $links[] = [
                'movie_link_id' => $movieLinkId,
                'title' => $title,
                'link' => $request->links[$key],
                'caption' => $request->captions[$key],
                'subtitle' => $request->subtitles[$key],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        return $links;
    }

    private function trailers($movieLinkId,$request){
        $trailers = [];
        foreach ($request->trailers as $key=>$trailer){
            $trailers[]=[
                'movie_link_id'=>$movieLinkId,
                'trailer'=>$trailer,
                'caption'=>$request->trailerCaptions[$key],
                'created_at'=>now(),
                'updated_at'=>now(),
            ];
        }
        return $trailers;
    }
}
