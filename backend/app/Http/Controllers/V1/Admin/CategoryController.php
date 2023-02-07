<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CategoryRequest;
use App\Http\Resources\V1\CategoryResourceCollection;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',Category::class);
        $categories = Category::with('children')->get();
        return new CategoryResourceCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $this->authorize('create',Category::class);
        $category = Category::create([
            'parent_id'=>$request->category_id,
            'title'=>$request->title,
            'slug'=>Str::of($request->title)->slug(),
            'status'=>$request->status
        ]);
        return $category ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        // $this->authorize('view',$category);
        return response(['status'=>'success','data'=>$category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('update',$category);
        $category->parent_id = $request->category_id;
        $category->title = $request->title;
        $category->status = $request->status;
        if($category->save()){
            $this->deleteAndUpdateAChildes($category,false,$category->status);
            return response(['status'=>'success']);
        }
        return response(['status'=>'error'],500);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete',$category);
        if($category->delete()){
            $this->deleteAndUpdateAChildes($category);
            return response(['status'=>'success']);
        }
        return response(['status'=>'error'],500);
    }

    private function deleteAndUpdateAChildes($category, $isDelete=true, $status='yes'){
        $childes = $category->children()->get();
        if(count($childes) > 0){
            foreach($childes as $child){
                if($isDelete){
                    $child->delete();
                }else{
                    $child->status = $status;
                    $child->save();
                }
                $this->deleteAndUpdateAChildes($child,$isDelete,$status);
            }
        }
    }
}
