<?php
    $style = [
        'container'=>'width:100%; background-color:#eee',
        'message-body'=>'background-color:white; text-align:center; padding:2rem; box-shadow: 0px 0px 8px 5px #dfdfdf;',
        'message'=>'color:black;word-break:break-all',
        'code'=>'background-color:black; color:white; padding:.5rem; border-radius:.2rem',
        'font'=>'font-size:.8rem',
        'center'=>'text-align:center'
    ]
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>کد تایید تغییر پروفایل</title>
</head>
<body>
    <div style="{{$style['container']}}">
        <div style="{{$style['message-body']}}">
            <p style="{{$style['message']}}">
                کابرا گرامی {{ $name }} کد تایید شما برای تغییر پروفایل برابر است با:
                <br><br><span style="{{$style["code"]}}">{{ $code }}</span>
            </p>
            <span><a href="{{ config('app.front_url') }}" style="{{$style['font']}}">با تشکر بزرگترین سایت فیلم و سریال ( {{ config('app.name') }} )</a></span>
        </div>

    </div>
</body>
</html>
