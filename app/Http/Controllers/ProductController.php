<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductOffersResource;
use App\Http\Resources\ProductOptionsResource;
use App\Http\Resources\ProductResource;
use App\Models\Image;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index():JsonResponse
    {
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $products = Product::authVendor($vendor)->paginate(10);
            return $this->respondWithSuccess(ProductResource::collection($products));
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
       
    }

 
    public function store(ProductRequest $request)
    {
        try { 

           $vendor = auth('vendor')->user();
            if($vendor){
                
            $product = Product::create([
                
                'name'  =>$request->name,
                'description'  =>$request->description,
                'price'  =>$request->price,
                'brand_id'  =>$request->brand_id,
                'category_id'  =>$request->category_id,
                'vendor_id'  =>$vendor->id,

            ]);

            if($request->hasFile('image')){
                
                $image = $request->file('image');
                $file_name = time().$image->getClientOriginalName();
                $file_path = public_path().'/products';
                $image->move($file_path,$file_name);

                Image::create([
                    'file_name' => $file_name,
                    'imageable_type' => 'App\Models\Product',
                    'imageable_id' => $product->id,
                ]);
            }
            
            return $this->respondWithSuccess(new ProductResource($product));
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage().$th->getLine());     
        }

    }


    public function show($id):JsonResponse
    { 
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $product = Product::authVendor($vendor)->findOrFail($id);
            return $this->respondWithSuccess(new ProductResource($product));
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function update(ProductRequest $request, $id):JsonResponse
    {
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $product = Product::authVendor($vendor)->findOrFail($id);
            
            $product->update([
                
                'name'  =>$request->name,
                'description'  =>$request->description,
                'price'  =>$request->price,
                'brand_id'  =>$request->brand_id,
                'category_id'  =>$request->category_id,
                'vendor_id'  =>$vendor->id,

            ]);
            
            return $this->respondWithSuccess(['message'=>'product is updated','data' =>new ProductResource($product)]);
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

  
    public function destroy($id):JsonResponse
    {
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $product = Product::authVendor($vendor)->findOrFail($id);
            $product->delete();
            
            return $this->respondWithSuccess(['message'=>'product is deleted','data' =>new ProductResource($product)]);
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

    public function restore($id):JsonResponse
    {
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $product = Product::withTrashed()->authVendor($vendor)->findOrFail($id);
            $product->restore();
                        
            return $this->respondWithSuccess(['message'=>'product is restored','data' => new ProductResource($product)]);
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    } 


    public function force_delete($id):JsonResponse
    {
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $product = Product::withTrashed()->authVendor($vendor)->findOrFail($id);
            $product->forceDelete();
            
            return $this->respondWithSuccess(['message'=>'product is deleted from database','data' => new ProductResource($product)]);
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }   

    public function add_options(Request $request)
    {

        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $product = Product::authVendor($vendor)->findOrFail($request->product_id);
            
            $product->options()->attach([$request->option_id]);
            return $this->respondWithSuccess( new ProductResource($product));
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }   
    }

    public function remove_options(Request $request)
    {

        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $product = Product::authVendor($vendor)->findOrFail($request->product_id);
            
            $product->options()->detach([$request->option_id]);
            return $this->respondWithSuccess( new ProductResource($product));
        }
        return $this->respondForbidden('you can not do this action');  
           
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }   
    }

    

        public function add_offer(Request $request)
        {

            try {
                $vendor = auth('vendor')->user();
                if($vendor){
                    
                $product = Product::authVendor($vendor)->findOrFail($request->product_id);
                
                $product->offers()->attach($request->offer_id);
                return $this->respondWithSuccess( new ProductResource($product));
            }
            return $this->respondForbidden('you can not do this action');  
            
            } catch (\Throwable $th) {
                return $this->respondError($th->getMessage());     
            }        
        }

        
        public function remove_offer(Request $request)
        {
            try {
                $vendor = auth('vendor')->user();
                if($vendor){
                    
                $product = Product::authVendor($vendor)->findOrFail($request->product_id);
                
                $product->offers()->detach($request->offer_id);
                return $this->respondWithSuccess( new ProductResource($product));
            }
            return $this->respondForbidden('you can not do this action');  
            
            } catch (\Throwable $th) {
                return $this->respondError($th->getMessage());     
            }   
        }


        public function search(Request $request)
        {
            if(empty($request->keyword)){
              return $this->respondError('enter the keyword');
            } else {
                
              $results =  search(Product::query() , $request->keyword);
                 
              return $this->respondWithSuccess($results);            

            }

        }




    
}