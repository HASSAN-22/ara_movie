<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PlanRequest;
use App\Http\Resources\V1\PlanResourceCollection;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',Plan::class);
        $plans = Plan::latest()->paginate();
        return new PlanResourceCollection($plans);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlanRequest $request)
    {
        $this->authorize('viewAny',Plan::class);
        $plan = Plan::create([
           'title'=>$request->title,
           'price'=>$request->price,
           'days'=>$request->days,
           'image'=>$request->image,
           'description'=>$request->description
        ]);
        return $plan ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        $this->authorize('view',$plan);
        return response(['status'=>'success','data'=>$plan]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        $this->authorize('update',$plan);
        $plan->title = $request->title;
        $plan->price = $request->price;
        $plan->days = $request->days;
        $plan->image = $request->image;
        $plan->description = $request->description;
        return $plan->save() ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        $this->authorize('delete',$plan);
        return $plan->delete() ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }
}
