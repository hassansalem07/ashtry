<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   
    public function index():JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('list')){
                
            $users = User::paginate(10);
            return $this->respondWithSuccess(UserResource::collection($users));
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
       
    }

 
    public function store(UserRequest $request):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('create')){
                
            DB::beginTransaction();

            $user = User::create([
                
                'name'  =>$request->name,
                'email'  =>$request->email,
                'password'  => Hash::make($request->password),
            ]);

            Wallet::create([
                'value' => 0,
                'user_id' => $user->id,
                
                ]);
                
            DB::commit();
            
            return $this->respondWithSuccess(new UserResource($user));
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondError($th->getMessage());     
        }

    }


    public function show($id):JsonResponse
    { 
        try {
            $admin = auth('admin')->user();
            if($admin->can('list')){
                
            $user = User::findOrFail($id);
            return $this->respondWithSuccess(new UserResource($user));
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function update(UserRequest $request, $id):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('update')){
                
            $user = User::findOrFail($id);
            
            $user->update([
                
                'name'  =>$request->name,
                'email'  =>$request->email,
                'password'  => Hash::make($request->password),
            ]);
            
            return $this->respondWithSuccess(['message'=>'user is updated','data' =>new UserResource($user)]);
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
                
            $user = User::findOrFail($id);
            $user->delete();
            
            return $this->respondWithSuccess(['message'=>'user is deleted','data' =>new UserResource($user)]);
        }
        return $this->respondForbidden('you can not do this action');  
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }
}