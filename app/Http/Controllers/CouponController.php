<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index():JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('list')){
                
            $coupons = Coupon::paginate(10);
            return $this->respondWithSuccess(CouponResource::collection($coupons));
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
       
    }

 
    public function store(CouponRequest $request):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('create')){
                
            $coupon = Coupon::create([
                
                'start'  =>$request->start,
                'code'  =>$request->code,
                'end'  =>$request->end,
                'type'  =>$request->type,
                'value'  =>$request->value,
            ]);
            
            return $this->respondWithSuccess(new CouponResource($coupon));
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }

    }


    public function show($id):JsonResponse
    { 
        try {
            $admin = auth('admin')->user();
            if($admin->can('list')){
                
            $coupon = Coupon::findOrFail($id);
            return $this->respondWithSuccess(new CouponResource($coupon));
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function update(CouponRequest $request, $id):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('update')){
                
            $coupon = Coupon::findOrFail($id);
            
            $coupon->update([
                
                'code'  =>$request->code,
                'start'  =>$request->start,
                'end'  =>$request->end,
                'type'  =>$request->type,
                'value'  =>$request->value,
            ]);
            
            return $this->respondWithSuccess(['message'=>'coupon is updated','data' =>new CouponResource($coupon)]);
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

  
    public function destroy($id):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('delete')){
                
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();
            
            return $this->respondWithSuccess(['message'=>'coupon is deleted','data' =>new CouponResource($coupon)]);
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }
}