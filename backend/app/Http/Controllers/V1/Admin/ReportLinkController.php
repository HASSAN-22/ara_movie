<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ReportLinkResourceCollection;
use App\Models\ReportLink;
use Illuminate\Http\Request;

use function App\Auxiliary\deleteNotification;

class ReportLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',ReportLink::class);
        $reports = ReportLink::latest()->with(['movie','movieLink','link'])->paginate();
        return new ReportLinkResourceCollection($reports);
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
     * @param  \App\Models\ReportLink  $reportLink
     * @return \Illuminate\Http\Response
     */
    public function show(ReportLink $reportLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReportLink  $reportLink
     * @return \Illuminate\Http\Response
     */
    public function edit(ReportLink $reportLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReportLink  $reportLink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReportLink $reportLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReportLink  $reportLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportLink $reportLink)
    {
        $this->authorize('delete',$reportLink);
        deleteNotification('App\Notifications\ReportLinkNotify',$reportLink->id,'report');
        return $reportLink->delete() ? response(['status'=>'success']) : response(['status','error'],500);
    }
}
