<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\DiscountRequest;
use App\Http\Resources\V1\DiscountResourceCollection;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',Discount::class);
        $discounts = Discount::latest()->with(['plan'=>fn($q)=>$q->select('id','title')])->paginate();
        return new DiscountResourceCollection($discounts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiscountRequest $request)
    {
        $this->authorize('create',Discount::class);
        $discount = Discount::create([
            'plan_id'=>$request->plan_id,
            'title'=>$request->title,
            'discount'=>$request->discount,
            'code'=>$request->code,
            'expire'=>$request->expire
        ]);
        return $discount ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        $this->authorize('view',$discount);
        $discount = $discount->where('id',$discount->id)->with(['plan'=>fn($q)=>$q->select('id','title')])->first();
        return response(['status'=>'success','data'=>$discount]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(DiscountRequest $request, Discount $discount)
    {
        $this->authorize('update',$discount);
        $discount->plan_id = $request->plan_id;
        $discount->title = $request->title;
        $discount->discount = $request->discount;
        $discount->code = $request->code;
        $discount->expire = $request->expire;
        return $discount->save() ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        $this->authorize('delete',$discount);
        return $discount->delete() ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }
}
