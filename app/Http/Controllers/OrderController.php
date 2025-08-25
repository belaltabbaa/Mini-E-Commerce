<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $carts = Cart::with('product')->where('user_id', Auth::user()->id)->get();
        if ($carts->isEmpty()) {
            return response()->json(['message' => 'cart is empty'], 400);
        }
        $totalPrice = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });
        $order = Order::create([
            'user_id' => Auth::user()->id,
            'total_amount' => $totalPrice,
            'status' => 'pending',
        ]);
        foreach ($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
                'subtotal' => $cart->product->price * $cart->quantity,
            ]);
        }
        Cart::where('user_id', Auth::user()->id)->delete();
        return response()->json(
            [
                'message' => 'Order created successfully',
                'order' => $order->load('items.product'), // جلب المنتجات التابعة للطلب
            ],
            201
        );
    }
}
