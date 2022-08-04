<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            
            'status' => $this->status,
            'total price'  =>$this->total_price,
            'discount value'  =>$this->discount_value,
            'sub total'  =>$this->sub_total,
            'paid'  =>$this->paid,
            'note' => $this->note,
            'user'  =>$this->user->name ?? null,
            'coupon'  =>$this->coupon->code ?? null,
            'driver'  =>$this->driver->name ?? null,

        ];    
    }
}