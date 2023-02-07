<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionFrontMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->exists('id') and $request->id != $request->user()->id){
            return response(['status'=>'error'],500);
        }
        return $next($request);
    }
}
