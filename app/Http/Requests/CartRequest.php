<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'please enter the product',
            'product_id.exists' => 'product not found',
            'qty.required' => 'please enter the quantity',
            'qty.numeric' => 'quantity must be number'
        ];
    }
}