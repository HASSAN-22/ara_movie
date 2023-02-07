<?php


namespace App\Auxiliary\Bank;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Zarinpal implements Portal
{
    private static array $errorMessage;
    private static array $portalData;
    private static array $productData;
    private static array $sellerData;
    private static string $callbackUrl;
    private static array $customData;
    private static        $instance = null;
    private static int $minute = 10;

    private function __construct()
    {

    }

    public static function setData(array $portalData, array $productData,array $customData=[], array $sellerData=[], string $callbackUrl=''){
        self::$portalData = $portalData;
        self::$productData = $productData;
        self::$sellerData = $sellerData;
        self::$callbackUrl = $callbackUrl;
        self::$customData = $customData;
        if(self::$instance == null){
            return self::$instance = new Zarinpal();
        }
        return self::$instance;
    }

    public static function setCache(): void{
        $data = json_encode(['portalData'=>self::$portalData,'productData'=>self::$productData,'customData'=>self::$customData]);
        Cache::put(self::$customData['key'].auth()->Id(), $data,now()->addMinutes(self::$minute));
    }
    public static function pay()
    {
        $data = array(
            "merchant_id" => self::$portalData['terminal_id'],
            "amount" => self::$productData['amount'],
            "callback_url" =>self:: $callbackUrl,
            "description" => self::$productData['description'],
            "metadata" => ["mobile"=> self::$sellerData['mobile'],"email"=> self::$sellerData['email']],
        );
        $jsonData = json_encode($data);
        $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/request.json');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'accept: application/json'
        ));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true, JSON_PRETTY_PRINT);
        curl_close($ch);
        if ($err) {
            self::$errorMessage = ['status'=>'error','data'=>"cURL Error #:" . $err];
        } else {
            if (empty($result['errors'])) {
                if ($result['data']['code'] == 100) {
                    return ['status'=>'success','data'=>'https://www.zarinpal.com/pg/StartPay/'. $result['data']["authority"]];
                }
            } else {
                self::$errorMessage = ['status'=>'error', 'data'=>self::errorCode($result['errors']['code'])];

            }
        }
        return ['status'=>'error','data'=>self::$errorMessage];
    }

    public static function verify()
    {
        $Authority = $_GET['Authority'];
        $data = array("merchant_id" => self::$portalData['terminal_id'], "authority" => $Authority, "amount" => self::$productData['amount']);
        $jsonData = json_encode($data);
        $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/verify.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'accept: application/json'
        ));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        if ($err) {
            self::$errorMessage = ['status'=>'error','data'=>"cURL Error #:" . $err];
        } else {
            if(!empty($result['errors'])){
                self::$errorMessage = ['status'=>'error', 'data'=>self::errorCode($result['errors']['code'])];
            }else{
                if ($result['data']['code'] == 100) {
                    return ['status'=>'success', 'data'=>['transaction_id:'=>$result['data']['ref_id']]];
                }
            }

        }
        return ['status'=>'error','data'=>self::$errorMessage];
    }

    public static function errorCode(int $code){
        switch ($code) {
            case -9 :
                return 'خطای اعتبار سنجی';
            case -10 :
                return 'آی‌پی و یا مرچنت كد پذیرنده صحیح نیست.';
            case -11 :
                return 'مرچنت کد فعال نیست؛ لطفا با تیم پشتیبانی ما تماس بگیرید.';
            case -12 :
                return 'تلاش بیش از حد در یک بازه زمانی کوتاه';
            case -15 :
                return 'ترمینال شما به حالت تعلیق در آمده است؛ با تیم پشتیبانی تماس بگیرید.';
            case -16 :
                return 'سطح تایید پذیرنده پایین‌تر از سطح نقره‌ای است.';
            case -30 :
                return 'اجازه دسترسی به تسویه اشتراکی شناور ندارید.';
            case -31 :
                return 'حساب بانکی تسویه را به پنل اضافه کنید؛ مقادیر وارد شده برای تسهیم درست نیست.';
            case -32 :
                return 'مقادیر وارد شده برای تسهیم درست نیست و از مقدار حداکثر بیشتر است.';
            case -33 :
                return 'درصدهای وارد شده درست نیست.';
            case -34 :
                return 'مبلغ از کل تراکنش بیشتر است.';
            case -35 :
                return 'تعداد افراد دریافت کننده تسهیم بیش از حد مجاز است.';
            case -40 :
                return 'مقادیر extra درست نیست؛ expire_in معتبر نیست.';
            case -50 :
                return 'مبلغ پرداخت شده با مقدار مبلغ در وریفای متفاوت است.';
            case -51 :
                return 'پرداخت ناموفق';
            case -52 :
                return 'خطای غیر منتظره؛ با پشتیبانی تماس بگیرید.';
            case -53 :
                return 'اتوریتی برای این مرچنت کد نیست.';
            case -54 :
                return 'اتوریتی نامعتبر است.';
            case -101 :
                return 'تراکنش قبلا وریفای شده است.';
            default:
                return 'خطای پیش بینی نشده‌ای رخ داده است.';
        }
    }
}
