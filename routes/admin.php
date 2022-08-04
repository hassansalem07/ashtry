<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use GuzzleHttp\Middleware;
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


Route::group([ 'prefix' => 'admin' , 'namespace'=>'Auth'],function (){
    
    Route::post('register', [AdminAuthController::class , 'register']);
    Route::post('login', [AdminAuthController::class , 'login']);
    Route::post('logout', [AdminAuthController::class , 'logout'])->middleware('auth:admin');
    Route::post('me', [AdminAuthController::class , 'me'])->middleware('auth:admin');
    Route::post('forget-password', [AdminAuthController::class , 'forget_password']);
    Route::post('reset-password', [AdminAuthController::class , 'reset_password']);

});


Route::group(['middleware' => ['auth:admin']],function(){
    
    Route::resource('admin', AdminController::class);

    Route::resource('vendor', VendorController::class);
    
    Route::resource('user', UserController::class);
    
    Route::resource('driver', DriverController::class);
    
    Route::resource('brand', BrandController::class);

    
    Route::get('brand/restore/{id}', [BrandController::class,'restore']);
    Route::get('brand/force-delete/{id}', [BrandController::class,'force_delete']);
    
    
    Route::resource('category', CategoryController::class);
    Route::get('category/restore/{id}', [CategoryController::class,'restore']);
    Route::get('category/force-delete/{id}', [CategoryController::class,'force_delete']);
    
    Route::get('change-status/{model}/{id}', StatusController::class);
    
    Route::resource('coupon', CouponController::class);
    
    Route::resource('role', AdminRoleController::class);
    Route::resource('permission', AdminPermissionController::class);
    
    
    Route::post('assign-permission-to-role',[PermissionController::class , 'assign_permission_to_role']);
    Route::post('remove-permission-from-role',[PermissionController::class , 'remove_permission_from_role']);
    Route::post('assign-role-to-admin',[AdminRoleController::class , 'assign_role_to_admin']);
    Route::post('remove-role-from-admin',[AdminRoleController::class , 'remove_role_from_admin']);
    
});