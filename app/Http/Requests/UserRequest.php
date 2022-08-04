<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
                    'email'    => 'required|email|unique:users,email',
                    'password' => 'required|min:8',
                ];
                
                break;
            
            case 'PUT':
                return [
                    'name' => 'required',
                    'email'    => 'required|email|unique:users,email,'.$this->segment(3),
                    'password' => 'required|min:8',
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
        ];
    }
}