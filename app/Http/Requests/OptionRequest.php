<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OptionRequest extends FormRequest
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
    public function rules():array
    {
        return [
            
            'name' => 'required',
            'value' => 'required',
        ];
    }

    public function messages():array
    {
        return [
            'name.required' => 'please enter the name',
            'value.required' => 'please enter the value',
        ];
    }
}