<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addtocart(Request $request, $productid)
    {
        $cart = Cart::where('user_id', Auth::user()->id)->where('product_id', $productid)->first();
        if ($cart) {
            $cart->quantity += $request->input('quantity', 1);
            $cart->save();
        } else {
            $cart = Cart::create([
                'user_id' => Auth::user()->id,
                'product_id' => $productid,
                'quantity' => $request->input('quantity', 1),
            ]);
        }
        return response()->json([
            'message' => 'product added to cart successfuly',
            'cart' => $cart->load('product'),
        ], 200);
    }
    public function deletefromcart($productid)
    {
        $cart = Cart::where('user_id', Auth::user()->id)->where('product_id', $productid)->first();
        if ($cart) {
            $cart->delete();
            return response()->json(['message' => 'تم الحذف بنجاح'], 403);
        }
        return response()->json(['message' => 'المنتج غير موجود'], 403);
    }
    public function showcartwithtotalprice()
    {
        $cart = Cart::with('product')->where('user_id', Auth::user()->id)->get();
        $totalPrice = $cart->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });
        return response()->json([
            'cart' => CartResource::collection($cart),
            'totalPrice' => $totalPrice,
        ]);
    }
}
