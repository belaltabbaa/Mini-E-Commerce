<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addtocart(Request $request, $productid)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1'
        ]);
        $cart = Cart::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $productid,
            ],
            [
                'quantity' => $request->quantity ?? 1
            ]

        );
        return response()->json([
            'message' => 'product added to cart successfuly',
            'cart' => $cart->load('product'),
        ], 200);
    }
}
