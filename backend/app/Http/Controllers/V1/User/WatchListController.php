<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\WatchListResourceCollection;
use App\Models\WatchList;
use Illuminate\Http\Request;

class WatchListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',WatchList::class);
        $watchLists = auth()->user()->watchLists()->with(['movie'=>fn($q)=>$q->select('id','title','imdb','image')])->latest()->paginate();
        return new WatchListResourceCollection($watchLists);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WatchList  $watchList
     * @return \Illuminate\Http\Response
     */
    public function show(WatchList $watchList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WatchList  $watchList
     * @return \Illuminate\Http\Response
     */
    public function edit(WatchList $watchList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WatchList  $watchList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WatchList $watchList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WatchList  $watchList
     * @return \Illuminate\Http\Response
     */
    public function destroy(WatchList $user_watch_list)
    {
        $this->authorize('viewAny',WatchList::class);
        return $user_watch_list->delete() ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }
}
