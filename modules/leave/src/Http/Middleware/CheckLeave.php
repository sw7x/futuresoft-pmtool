<?php

namespace Modules\Leave\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLeave
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
        //dd('111CheckLeave');

        //dd($request->is('*leavex'));
        if ($request->is('*leavex')) {
            dd('leave.middleware');
        }

        return $next($request);

    }
}
