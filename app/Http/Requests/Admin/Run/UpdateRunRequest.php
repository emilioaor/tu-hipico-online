<?php

namespace App\Http\Requests\Admin\Run;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRunRequest extends FormRequest
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
            'hippodrome_id' => 'required',
            'date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'hippodrome_id.required' => 'El hipódromo es requerido',
            'date.required' => 'La fecha es requerida',
        ];
    }
}
