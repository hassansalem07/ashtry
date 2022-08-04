<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'brands';
    protected $fillable = ['name','status'];




    public function products(){
        
        return $this->hasMany(Product::class , 'brand_id','id');
    }

    public function images(){
        
        return $this->morphMany('App\Models\Image' , 'imageable');
    }
    
    
    public function getStatusAttribute($status){
        
        return $status ? 'active' : "not active"; 
    }
    
}