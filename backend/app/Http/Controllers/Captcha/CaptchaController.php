<?php

namespace App\Http\Controllers\Captcha;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CaptchaController extends Controller
{
    public function siteKey(){
        $key = Crypt::encrypt(config('app.captcha_key'));
        return response(['key'=>$key]);
    }
}
