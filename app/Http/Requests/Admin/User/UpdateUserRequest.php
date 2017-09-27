<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|max:50',
            'newPassword1' => 'nullable|min:6|max:20',
            'newPassword2' => 'nullable|min:6|max:20',
            'top_sale' => 'required',
            'print_code' => 'required|between:6,7',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre no puede superar los 50 caracteres',
            'newPassword1.min' => 'La contraseña 1 debe poseer al menos 6 caracteres',
            'newPassword2.min' => 'La contraseña 2 debe poseer al menos 6 caracteres',
            'newPassword1.max' => 'La contraseña 1 no puede superar los 20 caracteres',
            'newPassword2.max' => 'La contraseña 2 no puede superar los 20 caracteres',
            'top_sale.required' => 'El tope en ventas es requerido',
            'print_code.required' => 'El código de impresión es requerido',
            'print_code.between' => 'El código de impresión debe contener entre :min y :max caracteres',
        ];
    }
}
