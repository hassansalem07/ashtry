<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'products';
    protected $fillable = ['name','description','price','brand_id','category_id','vendor_id'];


    
    public function brand(){
        
        return $this->belongsTo(Brand::class , 'brand_id','id');
    }

    
    public function category(){
        
        return $this->belongsTo(Category::class , 'category_id','id');
    }

    public function vendor(){
        
        return $this->belongsTo(Vendor::class , 'vendor_id','id');
    }

    public function options(){
        
        return $this->belongsToMany(Option::class , 'option_product', 'product_id', 'option_id');
    }
    
    public function offers(){
        
        return $this->belongsToMany(Offer::class , 'offer_product', 'product_id', 'offer_id');
    }

    public function users(){
        
        return $this->belongsToMany(User::class , 'carts', 'product_id', 'user_id')->withPivot('qty');
    }

    public function images(){
        
        return $this->morphMany('App\Models\Image' , 'imageable');
    }

    public function orders(){
        
        return $this->belongsToMany(Order::class , 'order_items', 'product_id', 'order_id')->withPivot('qty','price');
    }

    public function scopeAuthVendor($query , $vendor){
        return $query->where('vendor_id',$vendor->id);
     }
     
}