<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Config\ChangePasswordRequest;
use Hash;
use Auth;
use App\User;

class ConfigController extends Controller
{

    /**
     * Carga vista de configuracion del usuario
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function config() {
        return view('user.config');
    }

    /**
     * Cambio de contraseña del usuario
     *
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(ChangePasswordRequest $request) {

        if (! Hash::check($request->old_password, Auth::user()->password)) {
            $this->sessionMessage('La contraseña actual es incorrecta', 'alert-danger');
            return redirect()->route('user.config');
        }

        if ($request->new_password1 !== $request->new_password2) {
            $this->sessionMessage('Las contraseñas deben ser iguales', 'alert-danger');
            return redirect()->route('user.config');
        }

        if ($request->old_password === $request->new_password1) {
            $this->sessionMessage('La nueva contraseña es igual a la anterior', 'alert-danger');
            return redirect()->route('user.config');
        }

        $user = User::find(Auth::user()->id);
        $user->password = bcrypt($request->new_password1);
        $user->save();

        $this->sessionMessage('Contraseña actualizada');

        return redirect()->route('user.config');
    }
}
