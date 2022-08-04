<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index():JsonResponse
    {
        try {
            $user = auth('user')->user();
            $orders = Order::where('user_id',$user->id)->get();
            return $this->respondWithSuccess(OrderResource::collection($orders));
            
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
       
    }

 
    public function store(OrderRequest $request):JsonResponse
    {
        try {
            
            DB::beginTransaction();
               
            $user = auth('user')->user();
            $products = $user->products;
            $coupon = Coupon::where('code',$request->code)
                            ->whereDate('start','<=',Carbon::now())
                            ->whereDate('end','>=',Carbon::now())->first();


            $order = Order::create([
                'status'=>'pending',
                'note'  =>$request->note,
                'user_id'  =>$user->id,
                'coupon_id' => $coupon->id ?? null,
                'total_price' => 0,
            ]);
            
            foreach ($products as $product){

                OrderItem::create([
                'price' => $product->price ,
                'order_id' => $order->id,
                'product_id'=> $product->id,
                'qty' => $product->pivot->qty,
            ]);
            }
            
            $order->total_price += $product->price * $product->pivot->qty;

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

            $user->products()->detach($products->pluck('id')); 
            
            DB::commit();
             
        return $this->respondWithSuccess(new OrderResource($order));
        
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondError($th->getMessage());     
        }

    }


    public function show($id):JsonResponse
    { 
        try {
            $order = Order::findOrFail($id);
            return $this->respondWithSuccess(new OrderResource($order));
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }

   
    // public function update(OrderRequest $request, $id):JsonResponse
    // {
    //     try {
            
    //         $user = auth('user')->user();
    //         $order = Order::findOrFail($id);
    //         $total = 0;

            
    //         $order->update([
    //             'note'  =>$request->note,
    //             'user_id'  =>$user->id,
    //             'coupon_id' => $request->coupon ?? null,
    //             'total_price' => 0,
    //         ]);
            
    //         return $this->respondWithSuccess(['message'=>'option is updated','data' =>new OptionResource($option)]);
        
    //     } catch (\Throwable $th) {
    //         return $this->respondError($th->getMessage());     
    //     }
    // }

  
    public function destroy($id):JsonResponse
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            
            return $this->respondWithSuccess(['message'=>'order is deleted','data' =>new OrderResource($order)]);
        
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage());     
        }
    }
}