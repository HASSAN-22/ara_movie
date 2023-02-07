<?php


namespace App\Auxiliary\Bank;


use Illuminate\Support\Facades\Cache;

class Melat implements Portal
{

    private static array $errorMessage;
    private static array $portalData;
    private static array $productData;
    private static array $sellerData;
    private static string $callbackUrl;
    private static array $customData;
    private static        $instance = null;
    private static int $minute = 15;

    private function __construct()
    {
    }

    public static function pay()
    {
        $terminalId		= self::$portalData['terminal_id'];
        $userName		= self::$portalData['username'];
        $userPassword	= self::$portalData['password'];
        $orderId		= time();
        $amount 		= self::$productData['amount'];
        $callBackUrl	= self::$callbackUrl;
        $localDate		= date('Ymd');
        $localTime		= date('Gis');
        $additionalData	= self::$productData['description'];
        $payerId		= 0;

        $parameters = array(
            'terminalId' 		=> $terminalId,
            'userName' 			=> $userName,
            'userPassword' 		=> $userPassword,
            'orderId' 			=> $orderId,
            'amount' 			=> $amount,
            'localDate' 		=> $localDate,
            'localTime' 		=> $localTime,
            'additionalData' 	=> $additionalData,
            'callBackUrl' 		=> $callBackUrl,
            'payerId' 			=> $payerId
        );

        $client 	= new \nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
        $namespace 	='http://interfaces.core.sw.bps.com/';
        $result 	= $client->call('bpPayRequest', $parameters, $namespace);

        if ($client->fault)
        {
            self::$errorMessage=['status'=>'error','data'=>"خطا در اتصال به وب سرویس بانک"];
        } else {
            $err = $client->getError();
            if ($err)
            {
                self::$errorMessage=['status'=>'error','data'=>$err];
            } else {
                $res 		= explode (',',$result);
                $ResCode 	= $res[0];
                if ($ResCode == "0")
                {
                    return ['status'=>'success','data'=>self::bankForm($res,$parameters)];
                } else {
                    self::$errorMessage=['status'=>'error','data'=>self::errorCode($ResCode)];
                }
            }
        }
        return ['status'=>'error','data'=>self::$errorMessage];
    }

    public static function verify()
    {
        $terminalId		= self::$portalData['terminal_id'];
        $userName		= self::$portalData['username'];
        $userPassword	= self::$portalData['password'];

        $ResCode 		= (isset($_POST['ResCode']) && $_POST['ResCode'] != "") ? $_POST['ResCode'] : "";

        if ($ResCode == '0')
        {
            $client 				= new \nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
            $namespace 				='http://interfaces.core.sw.bps.com/';
            $orderId 				= (isset($_POST['SaleOrderId']) && $_POST['SaleOrderId'] != "") ? $_POST['SaleOrderId'] : "";
            $verifySaleOrderId 		= (isset($_POST['SaleOrderId']) && $_POST['SaleOrderId'] != "") ? $_POST['SaleOrderId'] : "";
            $verifySaleReferenceId 	= (isset($_POST['SaleReferenceId']) && $_POST['SaleReferenceId'] != "") ? $_POST['SaleReferenceId'] : "";

            $parameters = array(
                'terminalId' 		=> $terminalId,
                'userName' 			=> $userName,
                'userPassword' 		=> $userPassword,
                'orderId' 			=> $orderId,
                'saleOrderId' 		=> $verifySaleOrderId,
                'saleReferenceId' 	=> $verifySaleReferenceId
            );

            $result = $client->call('bpVerifyRequest', $parameters, $namespace);

            if($result == 0)
            {
                $result = $client->call('bpSettleRequest', $parameters, $namespace);

                if($result == 0)
                {
                   return ['status'=>'success', 'data'=>['transaction_id:'=>$verifySaleReferenceId]];

                } else {
                    $client->call('bpReversalRequest', $parameters, $namespace);
                    self::$errorMessage = ['status'=>'error', 'data'=>$result];
                }
            } else {
                $client->call('bpReversalRequest', $parameters, $namespace);
                self::$errorMessage = ['status'=>'error', 'data'=>self::errorCode($result)];
            }
        } else {
            self::$errorMessage = ['status'=>'error', 'data'=>self::errorCode($ResCode)];
        }
        return ['status'=>'error','data'=>self::$errorMessage];
    }

    public static function setData(array $portalData, array $productData, array $customData = [], array $sellerData = [], string $callbackUrl = '')
    {
        self::$portalData = $portalData;
        self::$productData = $productData;
        self::$sellerData = $sellerData;
        self::$callbackUrl = $callbackUrl;
        self::$customData = $customData;
        if(self::$instance == null){
            return self::$instance = new Melat();
        }
        return self::$instance;
    }

    public static function setCache()
    {
        $data = json_encode(['portalData'=>self::$portalData,'productData'=>self::$productData,'customData'=>self::$customData]);
        Cache::put(self::$customData['key'].auth()->Id(), $data,now()->addMinutes(self::$minute));
    }

    public static function errorCode(int $code)
    {
        switch ($code){
            case 0: return "تراکنش با موفقیت انجام شد";break;
            case 11: return "شماره کارت نامعتبر است";break;
            case 12: return " موجودی کافی نیست";break;
            case 13: return " رمز نادرست است";break;
            case 14: return "تعداد دفعات وارد کردن رمز بیش از حد مجاز است";break;
            case 15: return "کارت نامعتبر است";break;
            case 16: return "دفعات برداشت وجه بیش از حد مجاز است";break;
            case 17: return "کاربر از انجام تراکنش منصرف شده است";break;
            case 18: return "تاریخ انقضای کارت گذشته است";break;
            case 19: return "مبلغ برداشت وجه بیش از حد مجاز است";break;
            case 21: return "پذیرنده نامعتبر است";break;
            case 23: return "خطای امنیتی رخ داده است";break;
            case 24: return "اطلاعات کاربری پذیرنده نامعتبر است";break;
            case 25: return "مبلغ نامعتبر است";break;
            case 31: return "پاسخ نامعتبر است";break;
            case 32: return "فرمت اطلاعات وارد شده صحیح نمی باشد";break;
            case 33: return "حساب نامعتبر است";break;
            case 34: return "خطای سیستمی";break;
            case 35: return "تاریخ نامعتبر است";break;
            case 40: return "شماره درخواست تکراری است";break;
            case 42: return "یافت نشد Sale تراکنش";break;
            case 43: return "قبلا درخواست Verify داده شده است";break;
            case 44: return "درخواست Verfiy یافت نشد";break;
            case 45: return "تراکنش Settle (تسویه) شده است";break;
            case 46: return "تراکنش Settle (تسویه)نشده است";break;
            case 47: return "تراکنش Settle یافت نشد";break;
            case 48: return "تراکنش Reverse شده است";break;
            case 49: return "تراکنش Refund یافت نشد";break;
            case 51: return "تراکنش تکراری است";break;
            case 54: return "تراکنش مرجع موجود نیست";break;
            case 55: return "تراکنش نامعتبر است";break;
            case 61: return "خطا در واریز";break;
            case 111: return "صادر کننده کارت نامعتبر است";break;
            case 112: return "خطای سوییچ صادر کننده کارت";break;
            case 113: return "پاسخی از صادر کننده کارت دریافت نشد";break;
            case 114: return "دارنده کارت مجاز به انجام این تراکنش نیست";break;
            case 412: return "شناسه قبض نادرست است";break;
            case 413: return "شناسه پرداخت نادرست است";break;
            case 414: return "سازمان صادر کننده قبض نامعتبر است";break;
            case 415: return "زمان جلسه کاری به پایان رسیده است";break;
            case 416: return "خطا در ثبت اطلاعات";break;
            case 417: return "شناسه پرداخت کننده نامعتبر است";break;
            case 418: return "اشکال در تعریف اطلاعات مشتری";break;
            case 419: return "تعداد دفعات ورود اطلاعات از حد مجاز گذشته است";break;
            case 421: return "IP نامعتبر است";break;
            default: return 'خطای غیر منتظره ای رخ داده است';break;
        }
    }

    private static function bankForm($res,$parameters)
    {
        return '<form name="myform" class="myform" action="https://bpm.shaparak.ir/pgwchannel/startpay.mellat" method="POST">
							<input type="hidden" id="RefId" name="RefId" value="'. $res[1] .'">
							<input type="hidden" id="TerminalId" name="TerminalId" value="'. $parameters['terminalId'] .'">
							<input type="hidden" id="UserName" name="UserName" value="'. $parameters['userName'] .'">
							<input type="hidden" id="UserPassword" name="UserPassword" value="'. $parameters['userPassword'] .'">
							<input type="hidden" id="PayOrderId" name="PayOrderId" value="'. $parameters['orderId'] .'">
							<input type="hidden" id="PayAmount" name="PayAmount" value="'. $parameters['amount'] .'">
							<input type="hidden" id="PayDate" name="PayDate" value="'. $parameters['localDate'] .'">
							<input type="hidden" id="PayTime" name="PayTime" value="'. $parameters['localTime'] .'">
							<input type="hidden" id="PayAdditionalData" name="PayAdditionalData" value="'. $parameters['additionalData'] .'">
							<input type="hidden" id="PayCallBackUrl" name="PayCallBackUrl" value="'. $parameters['callBackUrl'] .'">
							<input type="hidden" id="PayPayerId" name="PayPayerId" value="'. $parameters['payerId'] .'">
		                    </form>
		                    <script type="text/javascript">window.onload = formSubmit; function formSubmit() { document.forms[0].submit(); }</script>';
    }
}
