<?php

namespace App\Http\Controllers\V1;

use App\Auxiliary\Bank\Pay;
use App\Http\Controllers\Controller;
use App\Models\VipTransaction;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function verify(Request $request){
        list($user_id, $data, $productData, $customData, $result) = $this->verifyResult($request);
        if($result['status'] == 'error'){
            Cache::put('message_'.$user_id,$result['data']['data'],now()->addMinutes(15));
            return redirect($customData['currentRoute']);
        }
        $wallet = WalletTransaction::create([
            'cart'=>$productData['cart'],
            'user_id'=>$user_id,
            'amount'=>$data['amount'],
            'transaction_id'=> $result['data']['transaction_id']
        ]);
        if($wallet){
            $wallet = Wallet::firstOrNew(['user_id'=>$user_id]);
            $wallet->amount += $productData['amount'];
            $wallet->save();
        }
        return redirect($customData['currentRoute']);
    }

    public function vipVerify(Request $request){
        list($user_id, $data, $productData, $customData, $result) = $this->verifyResult($request);
        if($result['status'] == 'error'){
            Cache::put('message_vip_'.$user_id,$result['data']['data'],now()->addMinutes(15));
            return redirect($customData['currentRoute']);
        }
        $vip = VipTransaction::create([
            'user_id' => $user_id,
            'cart' => $customData['cart'],
            'plan' => $customData['plan'],
            'discount' => $customData['discount'],
            'type' => 'bank',
            'price' => $customData['price'],
            'expire' =>$customData['expire'],
            'transaction_id' => $result['data']['transaction_id']
        ]);
        return redirect($customData['currentRoute']);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function verifyResult(Request $request): array
    {
        $user_id = decrypt($request->uid);
        Auth::loginUsingId($user_id);
        $key = $request->key . $user_id;
        $data = (array)json_decode(Cache::get($key));
        $portalData = (array)$data['portalData'];
        $productData = (array)$data['productData'];
        $customData = (array)$data['customData'];
        $portal = 'App\Auxiliary\Bank\\' . $portalData['portal'];
        Cache::forget($key);
        $result = Pay::verify($portal::setData($portalData, $productData));
        return array($user_id, $data, $productData, $customData, $result);
    }
}
