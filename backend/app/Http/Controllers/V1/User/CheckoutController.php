<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CheckoutRequest;
use App\Http\Resources\V1\User\CheckoutResourceCollection;
use App\Models\Checkout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Auxiliary\deleteNotification;
use function App\Auxiliary\sendNotification;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAnyUser',Checkout::class);
        $checkouts = auth()->user()->checkouts()->with('cart')->latest()->paginate();
        return new CheckoutResourceCollection($checkouts);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutRequest $request)
    {
        $this->authorize('createUser',Checkout::class);
        $user = auth()->user();
        $wallet = $user->wallet;
        if($wallet->amount < $request->amount){
            return response(['status'=>'error','errors'=>['file'=>['مبلغ درخواستی بیشتر از موجودی کیف پول شماست']]],422);
        }
        DB::beginTransaction();
        try{
            $checkout = Checkout::create([
                'user_id'=>$user->id,
                'cart_id'=>$request->cart_id,
                'amount'=>$request->amount,
                'status'=>'pending'
            ]);
            sendNotification($checkout->id,'App\Notifications\CheckoutNotify', 'admin');
            DB::commit();
            return response(['status'=>'success']);
        }catch(Exception $e){
            DB::rollBack();
            return response(['status'=>'error'],500);
        }


        return response(['status'=>'error'],500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function show(Checkout $checkout)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Checkout  $user_checkout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkout $user_checkout)
    {
        $this->authorize('updateUser',$user_checkout);
        if($user_checkout->status == 'pending'){
            $user_checkout->status = 'cancel';
            if($user_checkout->save()){
                deleteNotification('App\Notifications\CheckoutNotify',$user_checkout->id,'checkoutNotify');
                return response(['status'=>'success']);
            }
            return response(['status'=>'error'],500);
        }
        return response(['status'=>'error'],500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkout $checkout)
    {
        //
    }
}
