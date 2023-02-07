<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravolt\Avatar\Avatar;
use function App\Auxiliary\micro_time;
use function App\Auxiliary\removeFile;

class RegisterController extends Controller
{
    public function register(Request $request){
        $request->validate($this->rules());
        $user = User::create([
            'name'=>$request->name,
            'access'=>'user',
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'status'=>'yes',
            'password'=>Hash::make($request->password),
        ]);
        if($user){
            $token = $user->createNewToken($request->header('User-Agent'), $user);
            return response(['status'=>'success','token'=>$token,'id'=>$user->id,'access'=>$user->access,'name'=>$user->name,'avatar'=>$user->avatar]);
        }
        return response(['status'=>'error'],500);
    }


    private function rules(){
        return [
          "name"=>['required','string','max:255'],
          "mobile"=>['required','numeric','digits:11','unique:users'],
          "email"=>['required','string','email','max:255','unique:users'],
          "password"=>['required','string','confirmed','min:6','max:255']
        ];
    }
}
