<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SendMail;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserAuthController extends Controller
{
    
    public function register(UserRequest $request):JsonResponse
    {
        try {

            DB::beginTransaction();
                        
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
                
                ]);

                 Wallet::create([
                    'value' => 0,
                    'user_id' => $user->id,
                    
                    ]);
                    
            DB::commit();
           
               return $this->respondWithSuccess(new UserResource($user));
               
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondError($th->getMessage());     
        }
    

    }



    public function login():JsonResponse
    {
        try {
            $credentials = request(['email', 'password']);

            if (! $token = auth('user')->attempt($credentials)) {
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
            return $this->respondWithSuccess(new UserResource(auth('user')->user()));

        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    public function logout():JsonResponse
    {
        try {
            auth('user')->logout();

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
            'email' => 'required|email|exists:users,email'
        ]);
        
        if ($validator->fails()) {
             return $this->respondFailedValidation('please enter valid email');
             } 
             
             $user = User::where('email',$request->email)->first();
             $user->forget_code = Str::random(8);
             $user->forget_code_expire = Carbon::now()->addHours(5);
             $user->save();

             
             $data = [
                 'name' => $user->name,
                 'email' => $user->email,
                 'template' => 'forget-password',
                 'code' => $user->forget_code,
             ];

             SendMail::dispatch($data);
             
             return $this->respondWithSuccess(['we sent the code to your email']);
        
            
    }

    
    public function reset_password(Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'forget_code' => 'required|exists:users,forget_code',
            'new_password' => 'required',

        ]);
        
        if ($validator->fails()) {
             return $this->respondFailedValidation('please enter valid code and new password');
             } 

             $user = User::where('forget_code',$request->forget_code)->first();
             
             if($user->forget_code_expire > Carbon::now()){
                 return $this->respondError('the code is expired');
             } 
             
             $user->password = Hash::make($request->new_password);
             $user->save();
             
             return $this->respondWithSuccess(['message'=>'password is updated' , 'data'=>new UserResource($user)]);
    }
    
}