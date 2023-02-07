<?php


namespace App\Auxiliary\Bank;


use Illuminate\Http\Request;

interface Portal
{
    public static function pay();

    public static function verify();

    /**
     * @param array $portalData # [
            'terminal_id'=>"xxxxxxxx",
            'username'=>'username|null',
            'password'=>'password|null',
        ]
     * @param array $productData # [ 'amount'=>'amount to rial', 'description'=>'description|null'  ]
     * @param array $sellerData # [ 'email'=>'email|null', 'mobile'=>'mobile|null'  ]
     * @param string $callbackUrl
     * @param array $customData
     * @return mixed
     */
    public static function setData(array $portalData, array $productData, array $customData=[], array $sellerData=[], string $callbackUrl='');

    public static function setCache();

    /**
     * Convert error number to message
     * @param int $code
     */
    public static function errorCode(int $code);

}
