<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CartRequest;
use App\Http\Resources\V1\User\CartResourceCollection;
use App\Models\Cart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Auxiliary\bankName;
use function App\Auxiliary\sendNotification;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAnyUser',Cart::class);
        $carts = auth()->user()->carts()->latest()->paginate();
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
        $this->authorize('createUser',Cart::class);
        DB::beginTransaction();
        try{
            $cart = Cart::create([
                'user_id'=>auth()->Id(),
                'bank_name'=>bankName($request->cart),
                'cart'=>$request->cart,
                'shaba'=>$request->shaba,
                'status'=>'pending'
             ]);
             sendNotification($cart->id,'App\Notifications\CartNotify', 'admin');
             DB::commit();
            return response(['status'=>'success']);
        }catch(Exception $e){
            DB::rollBack();
            return response(['status'=>'error'],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
       //
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
        //
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
