<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Http\Resources\VendorResource;
use App\Jobs\SendMail;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class VendorAuthController extends Controller
{

    public function register(VendorRequest $request):JsonResponse
    {
        try {
            $vendor = Vendor::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
                
                ]);
           
                return $this->respondWithSuccess(new VendorResource($vendor));
                
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }

    }



    public function login():JsonResponse
    {
        try {
            $credentials = request(['email', 'password']);

        if (! $token = auth('vendor')->attempt($credentials)) {
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
            return $this->respondWithSuccess(new VendorResource(auth('vendor')->user()));
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function logout():JsonResponse
    {
        try {
            auth('vendor')->logout();

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
            'email' => 'required|email|exists:vendors,email'
        ]);
        
        if ($validator->fails()) {
             return $this->respondFailedValidation('please enter valid email');
             } 
             
             $vendor = Vendor::where('email',$request->email)->first();
             $vendor->forget_code = Str::random(8);
             $vendor->forget_code_expire = Carbon::now()->addHours(5);
             $vendor->save();

             
             $data = [
                 'name' => $vendor->name,
                 'email' => $vendor->email,
                 'template' => 'forget-password',
                 'code' => $vendor->forget_code,
             ];

             SendMail::dispatch($data);
             
             return $this->respondWithSuccess(['we sent the code to your email']);
        
            
    }

    
    public function reset_password(Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'forget_code' => 'required|exists:vendors,forget_code',
            'new_password' => 'required',

        ]);
        
        if ($validator->fails()) {
             return $this->respondFailedValidation('please enter valid code and new password');
             } 

             $vendor = Vendor::where('forget_code',$request->forget_code)->first();
             
             if($vendor->forget_code_expire > Carbon::now()){
                 return $this->respondError('the code is expired');
             } 
             
             $vendor->password = Hash::make($request->new_password);
             $vendor->save();
             
             return $this->respondWithSuccess(['message'=>'password is updated' , 'data'=>new VendorResource($vendor)]);
    }
}