<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required',
                    'email'    => 'required|email|unique:drivers,email',
                    'password' => 'required|min:8',
                    'city' => 'required',
                ];
                
                break;
            
            case 'PUT':
                return [
                    'name' => 'required',
                    'email'    => 'required|email|unique:drivers,email,'.$this->segment(3),
                    'password' => 'required|min:8',
                    'city' => 'required',

                ];
                break;
        }   
    }

    public function messages()
    {
        return [
            'name.required' => 'please enter your name',
            'email.required' => 'please enter your email',
            'email.email'    => 'this field must be an email',
            'email.unique'    => 'the email must be unique',
            'password.required' => 'please enter your password',
            'city.required' => 'please enter your city',

        ];
    }
}