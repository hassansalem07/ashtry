<?php

namespace App\Http\Controllers;

use App\Http\Requests\OptionRequest;
use App\Http\Resources\OptionResource;
use App\Models\Option;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index():JsonResponse
    {
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $options = Option::authVendor($vendor)->paginate(10);
            return $this->respondWithSuccess(OptionResource::collection($options));
        }
        return $this->respondUnAuthenticated('you are not authenticated');
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
       
    }

 
    public function store(OptionRequest $request)//:JsonResponse
    {
        try {
            $vendor = auth('vendor')->user();
            if($vendor){

            $option = Option::create([
                
                'name'  =>$request->name,
                'value'  =>$request->value,
                'vendor_id' => $vendor->id,
            ]);
            
            return $this->respondWithSuccess(new OptionResource($option));
        }
        return $this->respondUnAuthenticated('you are not authenticated');
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }

    }


    public function show($id):JsonResponse
    { 
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $option = Option::authVendor($vendor)->findOrFail($id);
            return $this->respondWithSuccess(new OptionResource($option));
        }
        return $this->respondUnAuthenticated('you are not authenticated');
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function update(OptionRequest $request, $id):JsonResponse
    {
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $option = Option::authVendor($vendor)->findOrFail($id);
            
            $option->update([
                
                'name'  =>$request->name,
                'value'  =>$request->value,
                'vendor_id' => $vendor->id,

            ]);
            
            return $this->respondWithSuccess(['message'=>'option is updated','data' =>new OptionResource($option)]);
        }
        return $this->respondUnAuthenticated('you are not authenticated');
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

  
    public function destroy($id):JsonResponse
    {
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $option = Option::authVendor($vendor)->findOrFail($id);
            $option->delete();
            
            return $this->respondWithSuccess(['message'=>'option is deleted','data' =>new OptionResource($option)]);
        }
        return $this->respondUnAuthenticated('you are not authenticated');
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }
}