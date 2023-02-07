<?php

namespace App\Http\Controllers\V1\User;

use App\Auxiliary\Bank\Pay;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\WalletTransactionRequest;
use App\Http\Resources\V1\User\WalletTransactionResourceCollection;
use App\Models\BankPortal;
use App\Models\Cart;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use function App\Auxiliary\portalBank;

class WalletTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAnyUser',WalletTransaction::class);
        $transactions = auth()->user()->walletTransactions()->latest()->paginate();
        return new WalletTransactionResourceCollection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WalletTransactionRequest $request)
    {
        $this->authorize('createUser',WalletTransaction::class);
        $portal = BankPortal::find($request->bank_portal_id);
        $cart = Cart::find($request->cart_id);
        $user = auth()->user();
        if(is_null($portal) or is_null($cart)){
            return response(['status'=>'error','errors'=>['file'=>['کارت یا در گاه بانکی وجود ندارد']]]);
        }

        $portalData=['terminal_id'=>$portal->code_id,'password'=>$portal->password,'username'=>$portal->username];
        $productData=['amount'=>$request->amount,'description'=>'شارژ کیف پول','cart'=>$cart->cart];
        $sellerData=['email'=>$user->email,'mobile'=>$user->mobile];
        $callbackUrl = config('app.url').'/api/verify?uid='.encrypt($user->id).'&key=wallet_trans_';
        $customData = ['currentRoute'=>$request->frontRoute,'key'=>'wallet_trans_'];
        return Pay::callPortal($portal->bank_name,$portalData,$productData,$customData,$sellerData,$callbackUrl);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(WalletTransaction $walletTransaction)
    {
        //
    }
}
