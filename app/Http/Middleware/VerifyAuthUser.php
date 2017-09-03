<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class VerifyAuthUser
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
        if (! Auth::check()) {
            Session::flash('alert-message', 'Debe iniciar sesión');
            Session::flash('alert-type', 'alert-danger');

            return redirect()->route('index.login');
        }

        return $next($request);
    }
}
