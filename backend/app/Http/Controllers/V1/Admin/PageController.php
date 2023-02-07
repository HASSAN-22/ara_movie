<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PageResourceCollection;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',Page::class);
        $pages = Page::latest()->paginate();
        return new PageResourceCollection($pages);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create',Page::class);
        $page = Page::create([
           'title'=>$request->title,
           'description'=>$request->description,
           'status'=>$request->status
        ]);
        return $page ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        $this->authorize('view',$page);
        return response(['status','success','data'=>$page]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $this->authorize('update',$page);
        $page->title = $request->title;
        $page->description = $request->description;
        $page->status = $request->status;
        return $page->save() ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $this->authorize('delete',$page);
        if(!in_array($page->id,[1,2])){
            $page->delete();
            return response(['status'=>'success'])
        }
        return response(['status'=>'error'],500);
    }
}
