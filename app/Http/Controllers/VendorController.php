<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VendorResource;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
   
    public function index():JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('list')){
                
            $vendors = Vendor::paginate(10);
            return $this->respondWithSuccess(VendorResource::collection($vendors));
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
       
    }

 
    public function store(VendorRequest $request):JsonResponse
    {
        try {

            $admin = auth('admin')->user();
            if($admin->can('create')){
                
            $vendor = Vendor::create([
                
                'name'  =>$request->name,
                'email'  =>$request->email,
                'password'  => Hash::make($request->password),
            ]);
            
            return $this->respondWithSuccess(new VendorResource($vendor));
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
                
            $vendor = Vendor::findOrFail($id);
            return $this->respondWithSuccess(new VendorResource($vendor));
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function update(VendorRequest $request, $id):JsonResponse
    {
        try {

            $admin = auth('admin')->user();
            if($admin->can('update')){
                
            $vendor = Vendor::findOrFail($id);
            
            $vendor->update([
                
                'name'  =>$request->name,
                'email'  =>$request->email,
                'password'  => Hash::make($request->password),
            ]);
            
            return $this->respondWithSuccess(['message'=>'vendor is updated','data' =>new VendorResource($vendor)]);
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
                
            $vendor = Vendor::findOrFail($id);
            $vendor->delete();
            
            return $this->respondWithSuccess(['message'=>'vendor is deleted','data' =>new VendorResource($vendor)]);
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }


    public function vendor_products(Request $request):JsonResponse
    {
        try {
                
            $products = Product::where('vendor_id',$request->vendor_id)->get();
            if(!count($products)){
                return $this->respondForbidden('this vendor doesn’t have products');  
            }
            return $this->respondWithSuccess(ProductResource::collection($products));
                
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
       
    }

}