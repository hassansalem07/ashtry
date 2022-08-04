<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = ['status','total_price','paid','note','user_id','coupon_id','driver_id'];

    public function products(){
        
        return $this->belongsToMany(Product::class , 'order_items', 'order_id', 'product_id')->withPivot('qty','price');
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id','id');
    }

    public function coupon(){
        return $this->belongsTo(Coupon::class , 'coupon_id','id');
    }

    public function driver(){
        return $this->belongsTo(Driver::class , 'driver_id','id');
    }

    public function getPaidAttribute($paid){
        
        return $paid ? 'paid' : "not paid"; 
    }
}