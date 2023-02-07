<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request){
        $request->validate($this->rules());
        if(Auth::attempt($request->only('mobile','password'))){
            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createNewToken($request->header('User-Agent'),$user);
            return response(['status'=>'success','token'=>$token,'id'=>$user->id,'access'=>$user->access,'name'=>$user->name,'avatar'=>$user->avatar]);
        }
        return response(['status'=>'error', 'errors'=>['files'=>['ایمیل یا رمز عبور اشتباه است']]],422);
    }

    public function logout(Request $request){
        if(auth()->check()){
            $user = auth()->user();
            $user->tokens()->delete();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return response(['status'=>'success']);
    }

    public function forgetPassword(Request $request){
        $request->validate(['email'=>['required','string','email','max:255']]);
        $user = User::where('email',$request->email)->first();
        if(is_null($user)){
            return response(['status'=>'error', 'errors'=>['files'=>['ایمیل وجود ندارد']]],422);
        }
        $token = Str::random(30);
        $this->deletePasswordReset($request->email);
        DB::table('password_resets')->insert(['email'=>$user->email,'token'=>$token,'created_at'=>now()]);
        Mail::send(new ForgetPasswordMail($user->email,$token));
        return response(['status'=>'success','msg'=>'یک ایمیل بازیابی برای شما ارسال شد']);
    }

    public function getResetPasswordToken(Request $request){
        list($token, $user) = $this->findResetPasswordToken($request->token,$request->email);
        return (!is_null($token) and !is_null($user)) ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    public function resetPassword(Request $request){
        $request->validate([
            'password'=>['required','string','confirmed','min:6','max:255'],
        ]);
        list($token, $user) = $this->findResetPasswordToken($request->token,$request->email);
        if(is_null($token) or is_null($user)){
            return response(['status'=>'error', 'errors'=>['files'=>['لینک بازیابی منقضی شده است لطفا دوباره درخواست را ارسال نمایید']]],422);
        }
        $user->password = Hash::make($request->password);
        if($user->save()){
            $this->deletePasswordReset($request->email);
            return response(['status'=>'success','msg'=>'رمز عبور با موفقیت تغییر یافت']);
        }
        return response(['status'=>'error'],500);
    }

    private function deletePasswordReset($email){
        DB::table('password_resets')->where('email',$email)->delete();
    }

    private function findResetPasswordToken($token,$email){
        $user = User::where('email',$email)->first();
        $token = DB::table('password_resets')->where('token',$token)->first();
        return [$token, $user];
    }

    private function rules(){
        return [
          'mobile'=>['required','numeric','digits:11'],
          'password'=>['required','string','min:6','max:255']
        ];
    }
}
