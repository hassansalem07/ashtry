<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            
            'code' => 'required',
            'start' => 'required|date',
            'end' => 'required|date',
            'type' => 'required',
            'value' => 'required|numeric',
        ];
    }

    public function messages():array
    {
        return [
            
            'code.required' => 'please enter the code',
            'start.required' => 'please enter the start date',
            'start.date' => 'the start must be date',
            'end.required' => 'please enter the end date',
            'end.date' => 'the end must be date',
            'type.required' => 'please enter the type',
            'value.required' => 'please enter the value',
            'value.numeric' => 'the value must be a number',

        ];
    }
}