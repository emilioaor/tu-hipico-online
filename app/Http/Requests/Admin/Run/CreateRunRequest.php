<?php

namespace App\Http\Requests\Admin\Run;

use Illuminate\Foundation\Http\FormRequest;

class CreateRunRequest extends FormRequest
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
            'public_id' => 'required|max:20',
            'hippodrome_id' => 'required',
            'date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'hippodrome_id.required' => 'El hipódromo es requerido',
            'date.required' => 'La fecha es requerida',
            'public_id.requierd' => 'El ID publico es requerido',
            'public_id.unique' => 'El ID publico ya esta en uso',
            'public_id.max' => 'El ID publico solo puede contener 20 caracteres'
        ];
    }
}
