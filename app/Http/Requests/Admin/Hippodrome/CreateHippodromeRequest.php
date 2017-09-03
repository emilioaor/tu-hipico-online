<?php

namespace App\Http\Requests\Admin\Hippodrome;

use Illuminate\Foundation\Http\FormRequest;

class CreateHippodromeRequest extends FormRequest
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
            'name' => 'required|max:40'
        ];
    }

    public function messages() {
        return [
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre no puede contener mas de 40 caracteres'
        ];
    }
}
