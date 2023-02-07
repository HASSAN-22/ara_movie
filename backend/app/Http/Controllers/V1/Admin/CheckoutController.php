<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CheckoutRequest;
use App\Http\Resources\V1\CheckoutResourceCollection;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Auxiliary\deleteNotification;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',Checkout::class);
        $checkouts = Checkout::with(['cart','user'=>fn($q)=>$q->select('id','name','mobile')])->latest()->paginate();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function show(Checkout $checkout)
    {
        $this->authorize('viewAny',Checkout::class);
        return response(['status'=>'success','data'=>$checkout]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkout $checkout)
    {
        $this->authorize('update',$checkout);
        $request->validate(['status'=>['required','string','in:paid,no,cancel,pending']]);
        DB::beginTransaction();
        try {
            $checkout->status = $request->status;
            $checkout->save();
            if($checkout->status == 'paid'){
                $wallet = $checkout->user->wallet;
                if($checkout->amount > $wallet->amount){
                    return response(['status'=>'error','errors'=>['file'=>['مبلغ درخواستی بیشتر از موجودی کیف پول کاربر میباشد']]],422);
                }
                $wallet->amount -= $checkout->amount;
                $wallet->save();
            }
            deleteNotification('App\Notifications\CheckoutNotify',$checkout->id,'checkoutNotify');
            DB::commit();
            return response(['status'=>'success']);
        }catch (\Exception $e){
            DB::rollBack();
            return response(['status'=>'error'],500);
        }

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
