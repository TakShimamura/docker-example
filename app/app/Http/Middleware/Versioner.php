<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Versioner
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
        $request->route()->forgetParameter('version');
        return $next($request);
    }
}
