<?php

namespace App\Http\Middleware;

use Aacotroneo\Saml2\Facades\Saml2Auth;
use Closure;
use Illuminate\Support\Facades\Auth;

class I1
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->guest()) {
            Saml2Auth::login();
        }

        app()->i1c = [
            'userid'    => Auth::guard('admin')->user()->getAttribute('userid'),
            'username'  => Auth::guard('admin')->user()->getAttribute('name'),
            ];

        return $next($request);
    }
}
