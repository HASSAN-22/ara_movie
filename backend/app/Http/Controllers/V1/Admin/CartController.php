<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CartRequest;
use App\Http\Resources\V1\CartResourceCollection;
use App\Models\Cart;
use Illuminate\Http\Request;

use function App\Auxiliary\deleteNotification;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',Cart::class);
        $carts = Cart::with(['user'=>fn($q)=>$q->select('id','name','mobile')])->latest()->paginate();
        return new CartResourceCollection($carts);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CartRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        $this->authorize('view',$cart);
        return response(['status'=>'success','data'=>['status'=>$cart->status,'id'=>$cart->id]]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        $this->authorize('update',$cart);
        $request->validate(['status'=>['required','string','in:yes,no,pending']]);
        $cart->status = $request->status;
        if($cart->save()){
            deleteNotification('App\Notifications\CartNotify',$cart->id,'cart');
            return response(['status'=>'success']);
        }
        return response(['status'=>'error'],500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
