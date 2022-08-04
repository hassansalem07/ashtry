<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index():JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('list')){
                
            $categories = Category::paginate(10);
            return $this->respondWithSuccess(CategoryResource::collection($categories));
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
       
    }

 
    public function store(CategoryRequest $request):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('create')){
                
            $category = Category::create([
                
                'name'  =>$request->name,
                'status'  =>$request->status,
                'parent_id' => $request->parent_id,
            ]);
            
            return $this->respondWithSuccess(new CategoryResource($category));
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
                
            $category = Category::with('main_category','sub_categories')->findOrFail($id);
            
            return $this->respondWithSuccess(new CategoryResource($category));
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function update(CategoryRequest $request, $id):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('update')){
                
            $category = Category::findOrFail($id);
            
            $category->update([
                
                'name'  =>$request->name,
                'status'  =>$request->status,
                'parent_id' => $request->parent_id,

            ]);
            
            return $this->respondWithSuccess(['message'=>'category is updated','data' =>new CategoryResource($category)]);
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
                
            $category = Category::findOrFail($id);
            
            if(count($category->sub_categories)){
                return $this->respondError('this category has sub categories');         
            }
            
            $category->delete();
            return $this->respondWithSuccess(['message'=>'category is deleted','data' =>new CategoryResource($category)]);
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
                
            $category = Category::withTrashed()->findOrFail($id);
            $category->restore();
                        
            return $this->respondWithSuccess(['message'=>'category is restored','data' =>new CategoryResource($category)]);
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
                
            $category = Category::withTrashed()->findOrFail($id);
            $category->forceDelete();
            
            return $this->respondWithSuccess(['message'=>'category is deleted from database','data' =>new CategoryResource($category)]);
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }   
}