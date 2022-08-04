<?php

namespace App\Http\Controllers;

use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{

    
    public function index()
    {
        try {
            $user = auth('user')->user();
            if($user){
            $wallet = Wallet::authUser($user)->first();
            return $this->respondWithSuccess(new WalletResource($wallet));
            }
            return $this->respondUnAuthenticated('you are not authenticated');
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }


}