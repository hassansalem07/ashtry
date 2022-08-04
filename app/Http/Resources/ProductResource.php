<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    
    
    public function toArray($request)
    {
        
        return [
            
            'id' => $this->id,
            'name'  =>$this->name,
            'description'  =>$this->description,
            'price'  =>$this->price,
            'brand'  => $this->brand ? $this->brand->name : null,
            'category'  =>$this->category ? $this->category->name : null,
            'vendor'  =>$this->vendor ? $this->vendor->name : null,
            'details' => OptionResource::collection($this->options),
            'offers'=> OfferResource::collection($this->offers),
            'image'  => $this->images ?? null, 
  
             
        ];
    }
}