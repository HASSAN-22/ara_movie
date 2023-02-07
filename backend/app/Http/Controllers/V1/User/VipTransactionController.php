<?php

namespace App\Http\Controllers\V1\User;

use App\Auxiliary\Bank\Pay;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\VipTransactionRequest;
use App\Http\Resources\V1\User\VipTransactionResourceCollection;
use App\Models\BankPortal;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Plan;
use App\Models\VipTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VipTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAnyUser',VipTransaction::class);
        $transactions = auth()->user()->vipTransactions()->latest()->paginate();
        return new VipTransactionResourceCollection($transactions);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VipTransactionRequest $request)
    {
        $this->authorize('createUser',VipTransaction::class);
        $discount = 0;
        $user = auth()->user();
        if(!is_null($user->vipTransactions()->where('expire','>',now())->first())){
            return response(['status'=>'error','errors'=>['file'=>['شما دارای یک اکانت فعال هستید']]],422);
        }
        if(!empty($request->discount)){
            $discount = Discount::where('code',$request->discount)->where('expire','>',now())->first();
            if(is_null($discount)){
                return response(['status'=>'error','errors'=>['file'=>['کد تخفیف  معتبر نمیباشد']]],422);
            }
            $discount = $discount->discount;
        }
        $cart = Cart::find($request->cart_id);
        $plan = Plan::find($request->plan_id);
        $planPrice = \App\Auxiliary\discount($plan->price,$discount);
        if(is_null($cart)){
            return response(['status'=>'error','errors'=>['file'=>['کارت  وجود ندارد']]],422);
        }
        if($request->type_pay == 'bank'){
            return $this->setPayData($request, $cart, $planPrice, $user, $plan, $discount);

        }else{
            $wallet = $user->wallet;
            if($wallet->amount < $planPrice){
                return response(['status'=>'error','errors'=>['file'=>['موجودی کیف پول کافی نیست']]],422);
            }
            return $this->insertVipTransaction($user, $cart, $plan, $discount, $planPrice, $wallet);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VipTransaction  $vipTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(VipTransaction $vipTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VipTransaction  $vipTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(VipTransaction $vipTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VipTransaction  $vipTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VipTransaction $vipTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VipTransaction  $vipTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(VipTransaction $vipTransaction)
    {
        //
    }

    public function choosePlan(Plan $plan){
        $this->authorize('viewAnyUser',VipTransaction::class);
        return response(['status'=>'success','data'=>$plan]);
    }


    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @param $cart
     * @param $plan
     * @param int $discount
     * @param $planPrice
     * @param $wallet
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    private function insertVipTransaction(?\Illuminate\Contracts\Auth\Authenticatable $user, $cart, $plan, int $discount, $planPrice, $wallet)
    {
        DB::beginTransaction();
        try {
            $vip = VipTransaction::create([
                'user_id' => $user->id,
                'cart' => $cart->cart,
                'plan' => $plan->title,
                'discount' => $discount,
                'type' => 'wallet',
                'price' => $plan->price,
                'expire' => now()->addDays($plan->days),
                'transaction_id' => Str::random(15)
            ]);
            $wallet->amount -= $planPrice;
            $wallet->save();
            DB::commit();
            return response(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['status' => 'error'],500);
        }
    }

    /**
     * @param $request
     * @param $cart
     * @param $planPrice
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @param $plan
     * @param int $discount
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    private function setPayData($request, $cart, $planPrice, ?\Illuminate\Contracts\Auth\Authenticatable $user, $plan, int $discount)
    {
        $portal = BankPortal::find($request->bank_portal_id);
        if (is_null($portal) or is_null($cart)) {
            return response(['status' => 'error', 'errors' => ['file' => ['کارت یا در گاه بانکی وجود ندارد']]],422);
        }
        $portalData = ['terminal_id' => $portal->code_id, 'password' => $portal->password, 'username' => $portal->username];
        $productData = ['amount' => $planPrice, 'description' => 'خرید اشتراک Vip', 'cart_id' => $cart->id];
        $sellerData = ['email' => $user->email, 'mobile' => $user->mobile];
        $callbackUrl = config('app.url') . '/api/vip-verify?uid=' . encrypt($user->id) . '&key=vip_trans_';
        $customData = [
            'currentRoute' => $request->frontRoute,
            'key' => 'vip_trans_',
            'cart' => $cart->cart,
            'plan' => $plan->title,
            'discount' => $discount,
            'price' => $plan->price,
            'expire' => now()->addDays($plan->days)
        ];
        return Pay::callPortal($portal->bank_name, $portalData, $productData, $customData, $sellerData, $callbackUrl);
    }
}
