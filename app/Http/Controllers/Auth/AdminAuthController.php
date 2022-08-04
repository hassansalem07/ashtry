<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Http\Resources\AdminResource;
use App\Jobs\SendMail;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AdminAuthController extends Controller
{
   
    public function register(AdminRequest $request):JsonResponse
    {
        try {
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
                
                ]);
           
                return $this->respondWithSuccess(new AdminResource($admin));
                
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
           }
    
    }


    public function login():JsonResponse
    {
        try {
            $credentials = request(['email', 'password']);

            if (! $token = auth('admin')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            return $this->respondWithToken($token);
            
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
      
    }


    public function me():JsonResponse
    {
        try {
            return $this->respondWithSuccess(new AdminResource(auth('admin')->user()));
            
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function logout():JsonResponse
    {
        try {
            auth('admin')->logout();

            return $this->respondWithSuccess(['message' => 'Successfully logged out']);
            
                } catch (\Throwable $th) {
                    return $this->respondError($th->getMessage());     
                }
       
    }

    protected function respondWithToken($token):JsonResponse
    {
        try {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
            
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    
    }

    public function forget_password(Request $request):JsonResponse
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:admins,email'
        ]);
        
        if ($validator->fails()) {
             return $this->respondFailedValidation('please enter valid email');
             } 
             
             $admin = Admin::where('email',$request->email)->first();
             $admin->forget_code = Str::random(8);
             $admin->forget_code_expire = Carbon::now()->addHours(5);
             $admin->save();

             
             $data = [
                 'name' => $admin->name,
                 'email' => $admin->email,
                 'template' => 'forget-password',
                 'code' => $admin->forget_code,
             ];

             SendMail::dispatch($data);
             
             return $this->respondWithSuccess(['we sent the code to your email']);
        
            
    }

    
    public function reset_password(Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'forget_code' => 'required|exists:admins,forget_code',
            'new_password' => 'required',

        ]);
        
        if ($validator->fails()) {
             return $this->respondFailedValidation('please enter valid code and new password');
             } 

             $admin = Admin::where('forget_code',$request->forget_code)->first();
             
             if($admin->forget_code_expire > Carbon::now()){
                 return $this->respondError('the code is expired');
             } 
             
             $admin->password = Hash::make($request->new_password);
             $admin->save();
             
             return $this->respondWithSuccess(['message'=>'password is updated' , 'data'=>new AdminResource($admin)]);
    }
}