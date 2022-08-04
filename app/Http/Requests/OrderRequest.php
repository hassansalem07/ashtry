<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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

    public function rules():array
    {
        return [
            
            'note'=> 'nullable',
            'user_id'=> 'nullable|exists:users,id',
            'coupon_id'=>'nullable|exists:coupons,id',
            'driver_id'=> 'nullable|exists:drivers,id',
        ];
    }

    public function messages():array
    {
        return [
            'user_id.exists'=> 'user not found',
            'coupon_id.exists'=> 'coupon not found',
            'driver.exists'=> 'driver not found',


        ];
    }
}