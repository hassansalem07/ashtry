<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index():JsonResponse
    {
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                $offers = Offer::authVendor($vendor)->paginate(15);
                return $this->respondWithSuccess(OfferResource::collection($offers));
            }
           return $this->respondUnAuthenticated('you are not authenticated');
            
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
       
    }

 
    public function store(OfferRequest $request):JsonResponse
    {
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
                
            $offer = Offer::create([
                
                'start'  =>$request->start,
                'end'  =>$request->end,
                'type'  =>$request->type,
                'value'  =>$request->value,
                'vendor_id' => $vendor->id,
            ]);
            
            return $this->respondWithSuccess(new OfferResource($offer));
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
               
            $offer = Offer::authVendor($vendor)->findOrFail($id);
            return $this->respondWithSuccess(new OfferResource($offer)); 
            }
            return $this->respondUnAuthenticated('you are not authenticated');

        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function update(OfferRequest $request, $id):JsonResponse
    {
        try {
            $vendor = auth('vendor')->user();
            if($vendor){
            $offer = Offer::authVendor($vendor)->findOrFail($id);
            
            $offer->update([
                
                'start'  =>$request->start,
                'end'  =>$request->end,
                'type'  =>$request->type,
                'value'  =>$request->value,
                'vendor_id' => $vendor->id,
            ]);
            
            return $this->respondWithSuccess(['message'=>'offer is updated','data' =>new OfferResource($offer)]);
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
                
            $offer = Offer::authVendor($vendor)->findOrFail($id);
            $offer->delete();
            
            return $this->respondWithSuccess(['message'=>'offer is deleted','data' =>new OfferResource($offer)]);
        }
        return $this->respondUnAuthenticated('you are not authenticated');
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

    
}