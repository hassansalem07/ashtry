<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';
    protected $fillable = ['name','status','parent_id'];

    

    public function products(){
        
        return $this->hasMany(Product::class , 'category_id','id');
    }

    public function sub_categories(){
        
        return $this->hasMany(Category::class , 'parent_id');
    }

    public function main_category(){
        
        return $this->belongsTo(Category::class , 'parent_id');
    }

    public function getStatusAttribute($status){
        
        return $status ? 'active' : "not active"; 
    }
}