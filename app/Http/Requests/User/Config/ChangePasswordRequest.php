<?php

namespace App\Http\Requests\User\Config;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'old_password' => 'required|min:6|max:20',
            'new_password1' => 'required|min:6|max:20',
            'new_password2' => 'required|min:6|max:20',
        ];
    }

    public function messages() {
        return [
            'old_password.required' => 'Debe ingresar la vieja contraseña',
            'old_password.min' => 'La vieja contraseña de contener minimo 6 caracteres',
            'old_password.max' => 'La vieja contraseña de contener maximo 20 caracteres',
            'new_password1.required' => 'Debe ingresar la nueva contraseña',
            'new_password1.min' => 'La nueva contraseña de contener minimo 6 caracteres',
            'new_password1.max' => 'La nueva contraseña de contener maximo 20 caracteres',
            'new_password2.required' => 'Debe ingresar la segunda contraseña',
            'new_password2.min' => 'La segunda contraseña de contener minimo 6 caracteres',
            'new_password2.max' => 'La segunda contraseña de contener maximo 20 caracteres',
        ];
    }
}
