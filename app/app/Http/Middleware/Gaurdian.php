<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Requests\Request;


class Gaurdian
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
        $user = $request->user();
        
        return $next($request, $user);
    }
}
