<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();
        return CategoryResource::collection($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string',
        ]);

        $category = Category::create($validated);

        return response()->json(['message' => 'Category created', 'category' => $category], 201);
    }
    public function show($id)
    {
        $category = Category::find($id);
        return response()->json(['category' => $category->load('products')]);
    }
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'nullable|string',
        ]);

        $category->update($validated);

        return response()->json(['message' => 'Category updated', 'category' => $category], 200);
    }
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return response()->json(['category' => 'تم الحذف']);
    }
}
