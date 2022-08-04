<?php

namespace App\Http\Controllers;

use App\Http\Requests\DriverRequest;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
   
    public function index(Request $request):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('list')){
                
            if($request->city){
                $drivers = Driver::where('city',$request->city)->get();
            } else {
                $drivers = Driver::paginate(10);
            }
            return $this->respondWithSuccess(DriverResource::collection($drivers));
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
       
    }

 
    public function store(DriverRequest $request):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('create')){
                
            $driver = Driver::create([
                
                'name'  =>$request->name,
                'email'  =>$request->email,
                'password'  => Hash::make($request->password),
                'city' => $request->city,
            ]);
            
            return $this->respondWithSuccess(new DriverResource($driver));
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
                
            $driver = Driver::findOrFail($id);
            return $this->respondWithSuccess(new DriverResource($driver));
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function update(DriverRequest $request, $id):JsonResponse
    {
        try {
            $admin = auth('admin')->user();
            if($admin->can('update')){
                
            $driver = Driver::findOrFail($id);
            
            $driver->update([
                
                'name'  =>$request->name,
                'email'  =>$request->email,
                'password'  => Hash::make($request->password),
                'city' => $request->city,

            ]);
            
            return $this->respondWithSuccess(['message'=>'driver is updated','data' =>new DriverResource($driver)]);
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
                
            $driver = Driver::findOrFail($id);
            $driver->delete();
            
            return $this->respondWithSuccess(['message'=>'driver is deleted','data' =>new DriverResource($driver)]);
        }
        return $this->respondForbidden('you can not do this action');   
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }
}