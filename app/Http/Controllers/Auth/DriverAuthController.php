<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverRequest;
use App\Http\Resources\DriverResource;
use App\Jobs\SendMail;
use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class DriverAuthController extends Controller
{

    public function register(DriverRequest $request):JsonResponse
    {
        try {
            $driver = Driver::create([
                'name' => $request->name,
                'email' => $request->email,
                'city' => $request->city,  
                'password' => Hash::make($request->password)
                
                ]);
           
                return $this->respondWithSuccess(new DriverResource($driver));
                
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }

    }



    public function login():JsonResponse
    {
        try {
            $credentials = request(['email', 'password']);

            if (! $token = auth('driver')->attempt($credentials)) {
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
            return $this->respondWithSuccess(new DriverResource(auth('driver')->user()));

        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function logout():JsonResponse
    {
        try {
            auth('driver')->logout();

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
            'email' => 'required|email|exists:drivers,email'
        ]);
        
        if ($validator->fails()) {
             return $this->respondFailedValidation('please enter valid email');
             } 
             
             $driver = Driver::where('email',$request->email)->first();
             $driver->forget_code = Str::random(8);
             $driver->forget_code_expire = Carbon::now()->addHours(5);
             $driver->save();

             
             $data = [
                 'name' => $driver->name,
                 'email' => $driver->email,
                 'template' => 'forget-password',
                 'code' => $driver->forget_code,
             ];

             SendMail::dispatch($data);
             
             return $this->respondWithSuccess(['we sent the code to your email']);
        
            
    }

    
    public function reset_password(Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'forget_code' => 'required|exists:drivers,forget_code',
            'new_password' => 'required',

        ]);
        
        if ($validator->fails()) {
             return $this->respondFailedValidation('please enter valid code and new password');
             } 

             $driver = Driver::where('forget_code',$request->forget_code)->first();
             
             if($driver->forget_code_expire > Carbon::now()){
                 return $this->respondError('the code is expired');
             } 
             
             $driver->password = Hash::make($request->new_password);
             $driver->save();
             
             return $this->respondWithSuccess(['message'=>'password is updated' , 'data'=>new DriverResource($driver)]);
    }
}