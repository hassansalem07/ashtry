<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';
    protected $fillable = ['start','end','type','value','vendor_id'];
    
    public function products(){
        
        return $this->belongsToMany(Product::class , 'offer_product', 'offer_id', 'product_id');
    }
    
    public function vendor(){
        
        return $this->belongsTo(Vendor::class , 'vendor_id');
    }

    public function scopeAuthVendor($query , $vendor){
       return $query->where('vendor_id',$vendor->id);
    }
}