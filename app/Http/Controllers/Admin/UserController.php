<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Requests\Admin\User\CreateUserRequest;


/**
 * Controlador para mantenimiento de usuario
 *
 * @author Emilio Ochoa <emilioaor@gmail.com>
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! empty($request->search)) {
            $users = $this->search($request->search, User::class, [
                'username',
                'name',
            ], 20);

            return view('admin.user.index')->with('users', $users);
        }

        $users = User::where('level', User::LEVEL_USER)
            ->where('status', '<>', User::STATUS_DELETED)
            ->orderBy('id', 'DESC')
            ->paginate(20)
        ;

        return view('admin.user.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $user = new User($request->all());

        $errors = $this->validatePassword($request->newPassword1, $request->newPassword2);

        if (! count($errors)) {
            $user->level = User::LEVEL_USER;
            $user->top_sale = ! empty($request->top_sale) ? $request->top_sale : 0;
            $user->password = bcrypt($request->newPassword1);
            $user->save();

            $this->sessionMessage('Usuario registrado');

            return redirect()->route('users.edit', ['user' => $user->id]);
        }

        return redirect()->route('users.create')->withErrors($errors);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        if ($user->status === User::STATUS_DELETED) {
            $this->sessionMessage('El usuario no existe', 'alert-danger');

            return redirect()->route('users.index');
        }

        return view('admin.user.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;

        $errors = $this->validatePassword($request->newPassword1, $request->newPassword2);

        if (! count($errors)) {
            $user->password = bcrypt($request->newPassword1);
            $user->top_sale = ! empty($request->top_sale) ? $request->top_sale : 0;
            $user->save();

            $this->sessionMessage('Usuario actualizado');
        }

        return redirect()
            ->route('users.edit', ['user' => $id])
            ->withErrors($errors);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->status = User::STATUS_DELETED;
        $user->save();

        $this->sessionMessage('Usuario eliminado');

        return redirect()->route('users.index');
    }

    /**
     * Ihabilita o habilita el usuario
     *
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus($userId) {

        $user = User::find($userId);

        if ($user->status === User::STATUS_ACTIVE) {

            $user->status = User::STATUS_INACTIVE;
            $message = 'Usuario inhabilitado';

        } elseif ($user->status === User::STATUS_INACTIVE) {

            $user->status = User::STATUS_ACTIVE;
            $message = 'Usuario habilitado';
        }

        $user->save();

        $this->sessionMessage($message);

        return redirect()->route('users.edit', ['user' => $user->id]);
    }


    /**
     * Valida que las contraseñas sean correctas antes
     * de cambiar
     *
     * @param $password1
     * @param $password2
     * @return array
     */
    private function validatePassword($password1, $password2)
    {
        $errors = [];

        if ((! empty($password1) && empty($password2) ) ||
            (empty($password1) && ! empty($password2) )) {

            $errors[] = 'Debe ingresar ambas contraseñas';

        } elseif (! empty($password1) && ! empty($password2) && $password1 !== $password2) {

            $errors[] = 'Las contraseñas deben ser iguales';
        }

        return $errors;
    }
}
