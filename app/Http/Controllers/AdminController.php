<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
   
    public function index():JsonResponse
    {
        try {
            $authAdmin = auth('admin')->user();
            if($authAdmin->can('manage admins')){
                
                $admins = Admin::paginate(10);
                return $this->respondWithSuccess(AdminResource::collection($admins));
            }
            return $this->respondForbidden('you can not do this action');   
            
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
       
    }

 
    public function store(AdminRequest $request):JsonResponse
    {
        try {
            $authAdmin = auth('admin')->user();
            if($authAdmin->can('manage admins')){
            $admin = Admin::create([
                
                'name'  =>$request->name,
                'email'  =>$request->email,
                'password'  => Hash::make($request->password),
            ]);
            
            return $this->respondWithSuccess(new AdminResource($admin));
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }

    }


    public function show($id):JsonResponse
    { 
        try {
            $authAdmin = auth('admin')->user();
            if($authAdmin->can('manage admins')){
                
            $admin = Admin::findOrFail($id);
            return $this->respondWithSuccess(new AdminResource($admin));
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function update(AdminRequest $request, $id):JsonResponse
    {
        try {
            $authAdmin = auth('admin')->user();
            if($authAdmin->can('manage admins')){
                
            $admin = Admin::findOrFail($id);
            
            $admin->update([
                
                'name'  =>$request->name,
                'email'  =>$request->email,
                'password'  => Hash::make($request->password),
            ]);
            
            return $this->respondWithSuccess(['message'=>'admin is updated','data' =>new AdminResource($admin)]);
        
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

  
    public function destroy($id):JsonResponse
    {
        try {
            
            $authAdmin = auth('admin')->user();
            if($authAdmin->can('manage admins')){
                
            $admin = Admin::findOrFail($id);
            $admin->delete();
            
            return $this->respondWithSuccess(['message'=>'admin is deleted','data' =>new AdminResource($admin)]);
        
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }
}