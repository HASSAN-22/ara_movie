<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ConfigSiteRequest;
use App\Models\ConfigSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function App\Auxiliary\setEnvironmentValue;

class ConfigSiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConfigSite  $configSite
     * @return \Illuminate\Http\Response
     */
    public function show(ConfigSite $configSite)
    {
        $this->authorize('view',$configSite);
        $contentTitle=config('menu.panel.configSite.contentTitle');
        $front=config('menu.front');
        return response(['status'=>'success','data'=>[$configSite,$contentTitle,$front]]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ConfigSite  $configSite
     * @return \Illuminate\Http\Response
     */
    public function update(ConfigSiteRequest $request, ConfigSite $configSite)
    {
        $this->authorize('update',$configSite);
        $user_id = auth()->Id();
        $configSite->site_name = $request->site_name;
        $configSite->email = $request->email;
        $configSite->mobile = $request->mobile;
        $configSite->phone = $request->phone;
        $configSite->logo = $request->logo;
        $configSite->logo_mobile = $request->logo_mobile;
        $configSite->copy_right = $request->copy_right;
        $configSite->telegram = $request->telegram;
        $configSite->twitter = $request->twitter;
        $configSite->facebook = $request->facebook;
        $configSite->instagram = $request->instagram;
        $configSite->omdb_api = $request->omdb_api;
//        $configSite->captcha_key = $request->captcha_key;
        $configSite->min_amount = $request->min_amount;
        $configSite->max_amount = $request->max_amount;
        $configSite->about_us = $request->about_us;
        $configSite->contact_us = $request->contact_us;
        $configSite->page = $request->page;
        $configSite->vip = $request->vip;
        $configSite->alert = $request->alert;
        $configSite->alert_link = $request->alert_link;
//        $configSite->captcha = $request->captcha;
        $configSite->front_link = $request->front_link;
        $configSite->bc_link = $request->bc_link;
        $configSite->description = $request->description;
        if($configSite->save()){
            setEnvironmentValue([
                'MIN_AMOUNT'=>$configSite->min_amount,
                'MAX_AMOUNT'=>$configSite->max_amount,
                'APP_NAME'=>"'$configSite->site_name'",
                'OMDB_API'=>$configSite->omdb_api,
                'FRONT_URL'=>$configSite->front_link,
                'APP_URL'=>$configSite->bc_link,
            ]);
            Auth::guard('web')->loginUsingId($user_id);
        }
        return response(['status'=>'success']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ConfigSite  $configSite
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConfigSite $configSite)
    {
        //
    }
}
