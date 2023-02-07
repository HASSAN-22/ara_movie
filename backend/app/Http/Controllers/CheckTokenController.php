<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckTokenController extends Controller
{
    public function checkToken(Request $request){
        $user = auth()->user();
        if(auth()->check()){
            return response(['status'=>'success','id'=>$user->id,'token_is_expire'=>$this->tokenIsExpire($user),'access'=>$user->access,'name'=>$user->name]);
        }
        return response(['status'=>'error']);
    }
    private function tokenIsExpire($user){
        $token = $user->tokens()->first();
        $tokenIsExpire = $token->created_at->addHour(3) > now();
        if($tokenIsExpire){
            $token->created_at = now();
            $token->save();
        }
        return $tokenIsExpire;
    }
}
