<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function App\Helpers\changeStatus;

class BrandController extends Controller
{
    
    public function index()//:JsonResponse
    {
        
        try {
            $admin = auth('admin')->user();
            if($admin->can('list')){

               $brands = Brand::paginate(1);
               
               $colection = BrandResource::collection($brands);
               
               return $this->respondWithSuccess(paginateResponse($brands,$colection));  
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
       
    }

 
    public function store(BrandRequest $request):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('create')){
          
            DB::beginTransaction();  
            $brand = Brand::create([
                
                'name'  =>$request->name,
                'status'  =>$request->status,
            ]);

            if($request->hasFile('image')){
                
                $image = $request->file('image');
                $file_name = time().$image->getClientOriginalName();
                $file_path = public_path().'/brands';
                $image->move($file_path,$file_name);

                Image::create([
                    'file_name' => $file_name,
                    'imageable_type' => 'App\Models\Brand',
                    'imageable_id' => $brand->id,
                ]);
            }
            
            DB::commit();
        
            return $this->respondWithSuccess(new BrandResource($brand));
        }
        return $this->respondForbidden('you can not do this action');            

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondError($th->getMessage());     
        }

    }


    public function show($id)//:JsonResponse
    { 
        try {
            $admin = auth('admin')->user();
            if($admin->can('list')){
                
            $brand = Brand::findOrFail($id);
            return $brand->images;
            return $this->respondWithSuccess(new BrandResource($brand));
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function update(BrandRequest $request, $id):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('update')){
                
            $brand = Brand::findOrFail($id);
            
            $brand->update([
                
                'name'  =>$request->name,
                'status'  =>$request->status,
            ]);
            
            return $this->respondWithSuccess(['message'=>'brand is updated','data' =>new BrandResource($brand)]);
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
                
            $brand = Brand::findOrFail($id);
            $brand->delete();
            
            return $this->respondWithSuccess(['message'=>'brand is deleted','data' =>new BrandResource($brand)]);
        }
        return $this->respondForbidden('you can not do this action');   
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());   
              
        }
    }

    public function restore($id):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('delete')){
                
            $brand = Brand::withTrashed()->findOrFail($id);
            $brand->restore();
                        
            return $this->respondWithSuccess(['message'=>'brand is restored','data' =>new BrandResource($brand)]);
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    } 


    public function force_delete($id):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('delete')){
                
            $brand = Brand::withTrashed()->findOrFail($id);
            $brand->forceDelete();
            
            return $this->respondWithSuccess(['message'=>'brand is deleted from database','data' =>new BrandResource($brand)]);
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }   

    

    
}