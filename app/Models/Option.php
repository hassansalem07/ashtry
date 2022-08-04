<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $table = 'options';
    protected $fillable = ['name','value','vendor_id'];


    public function products(){
        
        return $this->belongsToMany(Product::class , 'option_product' , 'option_id' , 'product_id');
    }

    public function scopeAuthVendor($query , $vendor){
        return $query->where('vendor_id',$vendor->id);
     }
}