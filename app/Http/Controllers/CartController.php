<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CartController extends Controller
{
    
    public function add_to_cart(CartRequest $request)
    {
                
        $user = auth('user')->user();

        if($user){

            // add to cart table when user is auth
            
        $products = $user->products()->where('products.id',$request->product_id)->first();

       if(!empty($products)){
        $user->products()->updateExistingPivot($request->product_id,['qty'=>$products->pivot->qty + $request->qty]);
        
       return $this->respondWithSuccess(CartResource::collection($user->products));

       } else {
        $user->products()->attach($request->product_id,['qty'=>$request->qty]);
 
        return $this->respondWithSuccess(CartResource::collection($user->products));

       }


    } else {

                    // add to redis when user not auth
    
    
        if(Redis::get('cart') == null){
            Redis::set('cart',json_encode([['product_id'=>$request->product_id,'qty'=>$request->qty]]));
            return json_decode(Redis::get('cart'));
            
        } else {
            $carts = json_decode(Redis::get('cart'));
            $newCart = [];
            $found = false;
            
            foreach($carts as $cart){
                if( $request->product_id == $cart->product_id ){
                    $cart->qty += $request->qty; 
                    $found = true;
                } 
                $newCart[] =$cart ;
            }
            
            if(!$found){
                $newCart[] = ['product_id'=>$request->product_id,'qty'=>$request->qty];
            }
                Redis::set('cart',json_encode($newCart));
            }
            
           return json_decode(Redis::get('cart'));  

        }

    }




    public function checkout(Request $request)
    {
        try {
            $carts = json_decode(Redis::get('cart'));
            $coupon = Coupon::where('code', $request->code)
                            ->whereDate('start', '<=', Carbon::now())
                            ->whereDate('end', '>=', Carbon::now())->first();
        
            $order = Order::create([
                'status' => 'pending',
                'total_price' => 0,
                'paid' => 0,
                'coupons_id' => $coupon->id ?? NUll,
                'users_id' => null,
            ]);
        
            foreach ($carts as $cart){
                
                $product = Product::find($cart->product_id);
                $order->products()->attach($product->id, ['qty' => $cart->qty, 'price' => $product->price]);
                $order->total_price += $product->price * $cart->qty;
            }
        
            $discount_value = 0;
            
            if (!empty($coupon)) {
                
                if ($coupon->type == 'percent') {
                    $discount_value = ($coupon->value / 100) * $order->total_price;
                    
                } else {
                    $discount_value = $coupon->value;
                }
            }
        
            $order->discount_value = $discount_value;
            $order->sub_total = $order->total_price - $discount_value;
            $order->save();
          
            return $this->respondWithSuccess($order->products);
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
        
    }

    public function remove_from_cart(Request $request)
    {
        
            // remove from cart table
        
        $user = auth('user')->user();

        $products = $user->products()->where('products.id',$request->product_id)->first();

       if(!empty($products) && $products->pivot->qty > 1 ){
        $user->products()->updateExistingPivot($request->product_id,['qty'=>$products->pivot->qty - 1]);
        
       return $this->respondWithSuccess(CartResource::collection($user->products));

       } else {
        $user->products()->detach($request->product_id);
        
        return $this->respondWithSuccess(CartResource::collection($user->products));

       }        

    }
    
    
}