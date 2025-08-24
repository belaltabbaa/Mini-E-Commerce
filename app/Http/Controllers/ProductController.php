<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json(['message' => 'success', 'product' => $product], 201);
    }
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return new ProductResource($product);
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'done delete']);
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }
}
