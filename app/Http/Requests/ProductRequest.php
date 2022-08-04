<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'vendor_id' => 'nullable|exists:vendors,id',

            
        ];
    }

    public function messages():array
    {
        return [
            'name.required' => 'please enter the name',
            'price.required' => 'please enter the price',
            'price.numeric' => 'the price must be number',
            'brand_id.exists'    => 'the brand not found',
            'category_id.exists'    => 'the category not found',
            'vendor_id.exists'    => 'the vendor not found',

        ];
    }
}