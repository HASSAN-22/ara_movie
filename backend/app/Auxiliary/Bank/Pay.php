<?php


namespace App\Auxiliary\Bank;


use Illuminate\Support\Facades\Cache;
use function App\Auxiliary\portalBank;

class Pay
{
    /**
     * Call portal and connect to bank
     * @param string $bank_name
     * @param array $portalData
     * @param array $productData
     * @param array $customData
     * @param array $sellerData
     * @param string $callbackUrl
     * @return \Illuminate\Http\Response
     */
    public static function callPortal($bank_name,$portalData,$productData,$customData,$sellerData,$callbackUrl){
        $portalBank = portalBank($bank_name);
        if(class_exists($portalBank)){
            $portalData['portal'] = class_basename($portalBank);
            $result = self::pay($portalBank::setData($portalData,$productData,$customData,$sellerData,$callbackUrl));
            return response(['data'=>$result,'portal'=>class_basename($portalBank)]);
        }
        return response(['status'=>'error'],500);
    }

    private static function pay(Portal $portal){
       $result = $portal::pay();
       if($result['status'] == 'success'){
           $portal->setCache();
           return $result;
       }
       return $result['data'];
    }

    public static function verify(Portal $portal){
        return $portal->verify();
    }
}
