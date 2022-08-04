<?php

use App\Http\Controllers\Auth\DriverAuthController;
use App\Http\Controllers\DriverController;
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


Route::group(['prefix' => 'driver' ,'namespace'=>'Auth'],function (){
    
    Route::post('register', [DriverAuthController::class , 'register']);
    Route::post('login', [DriverAuthController::class , 'login']);
    Route::post('logout', [DriverAuthController::class , 'logout'])->middleware('auth:driver');
    Route::post('me', [DriverAuthController::class , 'me'])->middleware('auth:driver');
    Route::post('forget-password', [DriverAuthController::class , 'forget_password']);
    Route::post('reset-password', [DriverAuthController::class , 'reset_password']);

});