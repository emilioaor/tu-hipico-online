<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|min:6|max:20',
            'name' => 'required|max:50',
            'newPassword1' => 'required|min:6|max:20',
            'newPassword2' => 'required|min:6|max:20',
            'top_sale' => 'required',
            'print_code' => 'required|between:6,7|unique:users',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'El nombre de usuario es requerido',
            'username.min' => 'El nombre de usuario debe poseer al menos 6 caracteres',
            'username.max' => 'El nombre de usuario no puede superar los 20 caracteres',
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre no puede superar los 50 caracteres',
            'newPassword1.required' => 'La contraseña 1 es requerido',
            'newPassword1.min' => 'La contraseña 1 debe poseer al menos 6 caracteres',
            'newPassword1.max' => 'La contraseña 1 no puede superar los 20 caracteres',
            'newPassword2.required' => 'La contraseña 2 es requerido',
            'newPassword2.min' => 'La contraseña 2 debe poseer al menos 6 caracteres',
            'newPassword2.max' => 'La contraseña 2 no puede superar los 20 caracteres',
            'top_sale.required' => 'El tope en ventas es requerido',
            'print_code.required' => 'El código de impresión es requerido',
            'print_code.between' => 'El código de impresión debe contener entre :min y :max caracteres',
            'print_code.unique' => 'El código de impresión esta siendo usado',
        ];
    }
}
