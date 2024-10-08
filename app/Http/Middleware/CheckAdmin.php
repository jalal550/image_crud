<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

     public function handle($request, Closure $next)
     {
         if ($request->user() && $request->user()->role !== 'admin') {
             return redirect('dashboard')->with('error', 'You do not have permission to access this resource');
         }
         return $next($request);
     }
}
