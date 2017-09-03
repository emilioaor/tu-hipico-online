<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

/**
 * Controlador de rutas sin autenticacion de usuario
 *
 * @author Emilio Ochoa <emilioaor@gmail.com>
 * @package App\Http\Controllers
 */
class DefaultController extends Controller
{

    /**
     * Carga vista de login de usuario
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm() {
        return view('index.login');
    }

    /**
     * Autenticacion de usuario
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userAuthentication(Request $request) {

        $user = User::where('username', $request->username)->first();

        if ($user && $user->status === User::STATUS_INACTIVE) {
            $this->sessionMessage('Usuario inhabilitado', 'alert-danger');

            return redirect()->route('index.login');
        }

        if ($user && $user->status === User::STATUS_DELETED) {
            $this->sessionMessage('Usuario o contraseña incorrectas', 'alert-danger');

            return redirect()->route('index.login');
        }

        if ($user && Auth::attempt( ['username' => $user->username, 'password' => $request->password] )) {

            if (Auth::user()->level == User::LEVEL_ADMIN) {
                return redirect()->route('admin.index');
            }

            return redirect()->route('user.index');
        }

        $this->sessionMessage('Usuario o contraseña incorrectas', 'alert-danger');

        return redirect()->route('index.login');
    }

    /**
     * Cierra la session del usuario
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout() {
        Auth::logout();

        return redirect()->route('index.login');
    }

}
