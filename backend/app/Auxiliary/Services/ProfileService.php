<?php


namespace App\Auxiliary\Services;


use App\Auxiliary\Uploader\Upload;
use App\Auxiliary\Uploader\Uploader;
use App\Http\Requests\V1\ProfileRequest;
use App\Mail\ConfirmUpdateProfileMail;
use App\Models\Code;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use function App\Auxiliary\removeFile;

class ProfileService
{
    public static function getProfile(User $user){
        if($user){
            $code = self::checkCode($user);
            Mail::send(new ConfirmUpdateProfileMail($user->email,$user->name,$code));
        }
        return response(['status'=>'success','data'=>$user]);
    }
    private static function checkCode($user){
        $code = (string) substr(rand(0000000,9999999),0,6);
        $user->codes()->delete();
        $result = Code::where('code',$code)->first();
        if(is_null($result)){
            Code::create(['user_id'=>$user->id,'code'=>$code]);
            return $code;
        }
        self::checkCode($user);
    }
    public static function updateProfile(ProfileRequest $request, User $user){
        if(auth()->Id() != $request->id){
            return response(['status'=>'error','errors'=>['files'=>['لطفا سشن های مروگر را تغییر ندهید در غیر این صورت پیگرد قانونی میشوید']]],422);
        }
        if(is_null($user->codes()->where('code',$request->confirm_code)->first())){
            return response(['stauts'=>'error','errors'=>['files'=>['کد تایید وارد شده اشتباه است و کد تایید جدیدی دوباره ارسال شد لطفا ایمیل خود را بررسی نمایید']]],422);
        }
        if(Hash::check($request->p_password,$user->password)){
//            if(!empty($_FILES['avatar'])){
//                $avatar = Uploader::upload(Upload::setOptions($_FILES['avatar'],'uploader/avatar/',false));
//                if($avatar['error'] != 0){
//                    return response(['status'=>'error','errors'=>['files'=>['اپلود اواتار با خطا مواجه شد']]],422);
//                }
//                removeFile($user->avatar);
//                $user->avatar = $avatar['data'];
//            }
            $user->name = $request->name;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            return $user->save() ? response(['status'=>'success']) : response(['status'=>'error'],500);
        }
        return response(['status'=>'error','errors'=>['files'=>['رمز عبور قبلی اشتباه است']]],422);
    }
}
