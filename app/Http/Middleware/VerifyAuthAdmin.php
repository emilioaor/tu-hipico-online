<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Auth;
use Session;

class VerifyAuthAdmin
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

        if (Auth::user()->level !== User::LEVEL_ADMIN) {

            Session::flash('alert-message', 'No tiene permisos para acceder a esta zona');
            Session::flash('alert-type', 'alert-danger');

            return redirect()->route('user.index');
        }

        return $next($request);
    }
}
