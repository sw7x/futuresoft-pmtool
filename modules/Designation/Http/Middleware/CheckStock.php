<?php

namespace Modules\Designation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckStock
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
        //dd($request->is('*config'));
        
        if ($request->is('*store')) {
        //if ($request->is('*config')) {
            // Logic only for URLs like /admin/...
            // You can also check user role, IP, etc.
            dd('config');
        }

        return $next($request);

    }
}
