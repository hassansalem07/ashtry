<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'wallets';
    protected $fillable = ['user_id','value'];

    public function user(){
        return $this->belongsTo(User::class , 'user_id','id');
    }

    public function scopeAuthUser($query , $user){
        return $query->where('user_id',$user->id);
    }
}