<?php


use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'user' ,'namespace'=>'Auth'],function (){
    
    Route::post('register', [UserAuthController::class , 'register']);
    Route::post('login', [UserAuthController::class , 'login']);
    Route::post('logout', [UserAuthController::class , 'logout'])->middleware('auth:user');
    Route::post('me', [UserAuthController::class , 'me'])->middleware('auth:user');
    Route::post('forget-password', [UserAuthController::class , 'forget_password']);
    Route::post('reset-password', [UserAuthController::class , 'reset_password']);


});

Route::get('search',[ProductController::class , 'search']);

Route::resource('order', OrderController::class);


Route::group(['middleware' => ['auth:user']],function(){



Route::resource('wallet', WalletController::class);


Route::get('add-to-cart',[CartController::class , 'add_to_cart']);
Route::get('remove-from-cart',[CartController::class , 'remove_from_cart']);
Route::get('checkout',[CartController::class , 'checkout']);



});