<?php


namespace App\Auxiliary;


use App\Auxiliary\Services\UserPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Laravolt\Avatar\Avatar;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

if(! function_exists('callPermission')){
    function callPermission($method, $model, $user){
        $model = is_object($model) ? get_class($model) : $model;
        return UserPermission::permission($method,$model,$user);
    }
}

if(! function_exists('getDevice')){
    function getDevice(string $userAgent){
        return explode(') ',explode(' (',$userAgent)[1])[0];
    }
}

if(! function_exists('dateToPersian')){
    function dateToPersian(string $date){
        return Jalalian::forge(dateConvertNumbers($date))->format('Y-m-d');
    }
}


if(! function_exists('dateToEnglish')){
    function dateToEnglish(string $date){
        return CalendarUtils::createCarbonFromFormat('Y-m-d', dateConvertNumbers($date))->format('Y-m-d');
    }
}

if(! function_exists('dateAgo')){
    function dateAgo(string $date){
        return Jalalian::forge($date)->ago();
    }
}

if(! function_exists('dateConvertNumbers')){
    function dateConvertNumbers(string $date, bool $englishNumber=true){
        return $englishNumber ? CalendarUtils::convertNumbers($date, true) : CalendarUtils::convertNumbers($date);
    }
}
if(! function_exists('portalBank')){
    function portalBank(string $name){
        $class = 'App\Auxiliary\Bank';
        switch ($name){
            case 'زرین پال':$class.='\Zarinpal';break;
            case 'ملت':$class.='\Melat';break;
            case 'پارسیان':$class.='\Parsian';break;
        }
        return $class;
    }
}
if(! function_exists('avatar')){
    function avatar($username,$path='avatars'){
        $avatar = new Avatar();
        $path = '/uploader/'.$path.'/'.micro_time().'.png';
        $fullPath = public_path($path);
        $avatar->create($username)->save($fullPath);
        return $path;
    }
}

if(! function_exists('discount')){
    function discount(string $price, string $percent, bool $format=false){
        $price = (int) $price;
        $percent = (int) $percent;
        $result = $price - (($percent / 100) * $price);
        return $format ? number_format($result) : $result;
    }
}

if(! function_exists('micro_time')){
    function micro_time(){
        return explode('.',explode(' ',microtime())[0])[1];
    }
}

if(! function_exists('setEnvironmentValue')){
    function setEnvironmentValue($envValues){
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        $str .= "\r\n";
        if (count($envValues) > 0) {
            foreach ($envValues as $envKey => $envValue) {

                $keyPosition = strpos($str, "$envKey=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                if (is_bool($keyPosition) && $keyPosition === false) {
                    $str .= "$envKey=$envValue";
                    $str .= "\r\n";
                } else {
                    $str = str_replace($oldLine, "$envKey=$envValue", $str);
                }
            }
        }

        $str = substr($str, 0, -1);
        if (!file_put_contents($envFile, $str)) {
            return false;
        }
        app()->loadEnvironmentFrom($envFile);
        if (file_exists(App::getCachedConfigPath())) {
            Artisan::call("config:cache");
        }
        return true;
    }
}

if(! function_exists('removeFile')){
    function removeFile($image){
        if(!empty($image)){
            if(file_exists(public_path($image))){
                unlink(public_path($image));
            }
        }
    }
}

if(! function_exists('status')){
    function statusAdmin($request){
        return auth()->user()->Admin() ? $request->status : 'pending';
    }
}


if(! function_exists('removeFiles')){
    function removeFiles($images){
        foreach ($images as $image){
            if(!empty($image)){
                if(file_exists(public_path($image))){
                    unlink(public_path($image));
                }
            }
        }
    }
}

if(! function_exists('slug')){
    function slug($text){
        return str_replace(' ','_',$text);
    }
}
if(! function_exists('paginate')){
    function paginate(Request $request, $items,$limit=10)
    {
        $total = count($items);
        $page = $request->page ?? 1;
        $perPage = $limit;
        $offset = ($page - 1) * $perPage;
        $items = array_slice($items, $offset, $perPage);
        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query()
        ]);
    }
}

if(! function_exists('currency')){
    function currency(){
        return ' '.config('app.currency').' ';
    }
}

if(! function_exists('access')){
    function access($access, $lang = 'fa'){
        if($lang == 'fa'){
            switch ($access){
                case 'admin': return 'مدیر سایت'; break;
                case 'user': return 'کاربر سایت'; break;
                default: return $access;
            }
        }else{
            switch ($access){
                case 'مدیر سایت': return 'admin'; break;
                case 'کاربر سایت': return 'user';break;
                default: return $access;
            }
        }

    }
}
if(! function_exists('active')){
    function active($status, $lang = 'fa'){
        if($lang == 'fa'){
            switch ($status){
                case 'yes': return 'فعال'; break;
                case 'no': return 'غیر فعال'; break;
                default: return $status;
            }
        }else{
            switch ($status){
                case 'فعال': return 'yes'; break;
                case 'غیر فعال':
                case 'غیرفعال': return 'no'; break;
                default: return $status;
            }
        }

    }
}
if(! function_exists('bankList')){
    function bankList(){
        return [
            "بانک ملی ایران	"=>"603799",
            "بانک سپه"=>"589210",
            "بانک توسعه صادرات"=>"627648",
            "بانک صنعت و معدن"=>"627961",
            "بانک کشاورزی"=>"603770",
            "بانک مسکن"=>"628023",
            "پست بانک ایران"=>"627760",
            "بانک توسعه تعاون"=>"502908",
            "بانک اقتصاد نوین"=>"627412",
            "بانک پارسیان"=>"622106",
            "بانک پاسارگاد"=>"502229",
            "بانک کارآفرین"=>"627488",
            "بانک سامان"=>"621986",
            "بانک سینا"=>"639346",
            "بانک سرمایه"=>"639607",
            "بانک تات"=>"636214",
            "بانک شهر"=>"502806",
            "بانک دی"=>"502938",
            "بانک صادرات"=>"603769",
            "بانک ملت"=>"610433",
            "بانک تجارت"=>"627353",
            "بانک رفاه"=>"589463",
            "بانک انصار"=>"627381",
            "بانک مهر اقتصاد"=>"639370",
        ];
    }
}
if(! function_exists('bankName')){
    function bankName(string $cart){
        $cart = substr($cart,0,6);
        foreach (bankList() as $bankName=>$number){
            if($number == $cart){
                return $bankName;
            }
        }
        return 'N/A';

    }
}
if(! function_exists('advertising')) {
    function advertising($text)
    {
        switch ($text){
            case 'image': return 'تصویری (انتهای مطلب، سایدبار، ابتدای مطلب)';break;
            case 'text': return 'متنی (انتهای مطلب، سایدبار، ابتدای مطلب)';break;
            case 'floating_image': return 'شناور تصویری (فقط انتهای سایدبار)';break;
        }
    }
}
if(! function_exists('status')){
    function status($status, $lang="fa"){
        if($lang == 'fa'){
            switch ($status){
                case 'yes' : return 'تایید شد'; break;
                case 'no' : return 'رد شد'; break;
                case 'pending' : return 'در حال بررسی'; break;
                case 'cancel' : return 'لغو شد'; break;
                case 'paid' : return 'پرداخت شد'; break;
                default: return $status;
            }
        }else{
            switch ($status){
                case 'تایید شد': return 'yes'; break;
                case 'رد شد': return 'no'; break;
                case 'در حال بررسی': return 'pending'; break;
                case 'لغو شد': return 'cancel'; break;
                case 'پرداخت شد': return 'paid'; break;
                default: return $status;
            }
        }

    }
}

if(! function_exists('sendNotification')){
   function sendNotification($post_id,$model,$user){
       if($user=='admin'){
           $users = User::where('access','admin')->get();
           foreach($users as $user){
               $user->notify(new $model($post_id));
           }
       }else{
           $user->notify(new $model($post_id));
       }
   }
}

if(! function_exists('deleteNotification')){
    function deleteNotification($type,$post_id,$key){
        DatabaseNotification::where('type',$type)->get()->map(function($item)use($post_id,$key){
            $id = $item['data'][$key]['id'];
            if($id == $post_id){
                $item->delete();
            }
        });
    }
}

if(! function_exists('getChartData')){
    function getChartData($model){
        setlocale(LC_TIME, 'fa');
        \Carbon\Carbon::setLocale('fa');
        $persian_month = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
        $users =$model->selectRaw('year(created_at) year, month(created_at) month,  day(created_at) day, count(*) count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->get();
        $labels = $dataset = [];
        foreach($users as $user) {
            foreach($persian_month as $key=>$month){
                $date = $user->year.'-'.$key;
                if($key == $user->month){
                    $date .= '-'.$user->day;
                    $labels[] = \Morilog\Jalali\Jalalian::forge(\Carbon\Carbon::parse($date))->format('%B %Y');
                    $dataset[] = $user->count;
                }else{
                    $date .= '-1';
                    $labels[] = \Morilog\Jalali\Jalalian::forge($date)->format('%B %Y');
                    $dataset[] = 0;
                }
            }

        };
        return [
            "labels" => $labels,
            "dataset" => $dataset,
        ];
    }
}


if(! function_exists('hashNumber')){
    function hashNumber($number, Bool $encode = true){
        if($encode == false){
            $number = base64_decode($number);
        }
        $result = ((0x0000000F & $number) << 4) + ((0x000000F0& $number)>>4)
            + ((0x00000F00 & $number) << 4) + ((0x0000F000& $number)>>4)
            + ((0x000F0000 & $number) << 4) + ((0x00F00000& $number)>>4)
            + ((0x0F000000 & $number) << 4) + ((0xF0000000& $number)>>4);
        return $encode ? base64_encode($result) : $result;
    }
}
if(! function_exists('checkFavorites')){
    function checkFavorites($favorites){
        $favorites = gettype($favorites) == 'array' ? $favorites : $favorites->toArray();
        if(!empty($favorites[0])){
            $result = array_filter($favorites,function($item){
                return $item['user_id'] == auth()->Id();
            });
            return empty($result[0]) ? true : false;
        }
        return true;
    }
}
if(! function_exists('notifyCount')){
    function notifyCount($count,$color){
        if($count > 0){
            return '<span class="label label-'.$color.' pull-left font-12">'.$count.'</span>';
        }
    }
}
if(! function_exists('readNotify')){
    function readNotify($id,$key,$model){
        foreach(auth()->user()->unreadNotifications()->where('type','App\Notifications\\'.$model)->get() as $notify){
            if($notify->data[$key]['id'] == $id){
                $notify->update(['read_at' => now()]);
            }
        }
    }
}
if(! function_exists('alertInList')){
    function alertInList($notifications,$key,$id){
        foreach($notifications as $notify){
            if($notify->data[$key]['id'] == $id){
                return '<span class="label label-danger pull-left font-12">جدید</span>';
            }
        }
    }
}
if(! function_exists('strict')){
    function strict($enable=false){
        /**
         * true doesn't work
         * false is work
         */
        config()->set('database.connections.mysql.strict', $enable);
        \DB::reconnect();
    }
}
