<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AjaxOnlyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        if($request->ajax()) {
            return $next($request);
        } else {
            $redirect_url = str_replace('api','',$request->path());
            return redirect($redirect_url);
        }
        
    }
}
