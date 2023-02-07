<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BankPortalRequest;
use App\Http\Resources\V1\BankPortalResourceCollection;
use App\Models\BankPortal;
use Illuminate\Http\Request;

class BankPortalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',BankPortal::class);
        $portals = BankPortal::latest()->paginate();
        return new BankPortalResourceCollection($portals);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankPortalRequest $request)
    {
        $this->authorize('create',BankPortal::class);
        $portal = BankPortal::create([
           'bank_name'=>$this->convertBankName($request->bank_name),
           'code_id'=>$request->code_id,
           'username'=>$request->username,
           'password'=>$request->password,
           'status'=>$request->status,
        ]);
        return $portal ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankPortal  $bankPortal
     * @return \Illuminate\Http\Response
     */
    public function show(BankPortal $bankPortal)
    {
        $this->authorize('view',$bankPortal);
        return response(['status'=>'success','data'=>$bankPortal]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankPortal  $bankPortal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankPortal $bankPortal)
    {
        $this->authorize('update',$bankPortal);

        $bankPortal->bank_name=$this->convertBankName($request->bank_name);
        $bankPortal->code_id=$request->code_id;
        $bankPortal->username=$request->username;
        $bankPortal->password=$request->password;
        $bankPortal->status=$request->status;

        return $bankPortal->save() ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankPortal  $bankPortal
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankPortal $bankPortal)
    {
        $this->authorize('delete',$bankPortal);
        return $bankPortal->delete() ? response(['status'=>'success']) : response(['status'=>'error'],500);

    }

    private function convertBankName($name){
        $bank_name = '';
        switch ($name){
            case 'parsian': $bank_name = 'پارسیان';break;
            case 'melat': $bank_name = 'ملت';break;
            case 'zarinpal': $bank_name = 'زرین پال';break;
        }
        return $bank_name;
    }
}
