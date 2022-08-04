<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
                    'name' => 'required|unique:categories,name',
                    'status' => 'required|boolean',
                    'parent_id' => 'nullable|exists:categories,id'
                ];
                
                break;
            
            case 'PUT':
                return [
                    'name' => 'required|unique:categories,name,'.$this->segment(3),
                    'status' => 'required|boolean',
                    'parent_id' => 'nullable|exists:categories,id'

                ];
                break;
        }   
    }
    

    public function messages():array
    {
        return [
            'name.required' => 'please enter the name',
            'name.unique'    => 'the name must be unique',
            'status.required' => 'please enter the status',
            'status.boolean'    => 'the name must be boolean',
        ];
    }
}