<?php


use App\Http\Controllers\Auth\VendorAuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
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


Route::group([ 'prefix' => 'vendor' ,'namespace'=>'Auth'],function (){
    
    Route::post('register', [VendorAuthController::class , 'register']);
    Route::post('login', [VendorAuthController::class , 'login']);
    Route::post('logout', [VendorAuthController::class , 'logout'])->middleware('auth:vendor');
    Route::post('me', [VendorAuthController::class , 'me'])->middleware('auth:vendor');
    Route::post('forget-password', [VendorAuthController::class , 'forget_password']);
    Route::post('reset-password', [VendorAuthController::class , 'reset_password']);
    
});


Route::post('vendor-products', [VendorController::class,'vendor_products']);


Route::group(['middleware' => ['auth:vendor',]],function(){


Route::resource('product', ProductController::class);



Route::resource('offer', OfferController::class);

Route::resource('option', OptionController::class);


Route::group(['prefix'=>'product'],function(){
    
    Route::get('restore/{id}', [ProductController::class,'restore']);
    Route::get('force-delete/{id}', [ProductController::class,'force_delete']);
    Route::post('add-offers', [ProductController::class,'add_offer']);
    Route::post('remove-offers', [ProductController::class,'remove_offer']);
    Route::post('add-options', [ProductController::class,'add_options']);  
    Route::post('remove-options', [ProductController::class,'remove_options']);  

});


Route::resource('image', ImageController::class);


});