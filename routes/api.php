<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    //api Resource Product//////
    Route::post('addproduct', [ProductController::class, 'store'])->middleware('IsAdmin');
    Route::post('updateproduct/{id}', [ProductController::class, 'update'])->middleware('IsAdmin');
    Route::delete('destroyproduct', [ProductController::class, 'destroy'])->middleware('IsAdmin');
    Route::get('detailsproduct/{id}', [ProductController::class, 'show']);
    Route::get('getallproduct', [ProductController::class, 'index']);
    ////////////////////////////

    Route::post('add/cart/{product}', [CartController::class, 'addtocart']);

    Route::delete('delete/cart/{product}', [CartController::class, 'deletefromcart']);

    Route::get('show/cart', [CartController::class, 'showcartwithtotalprice']);

    Route::post('add/order', [OrderController::class, 'createOrder']);

    Route::get('show/orders', [OrderController::class, 'showorders']);

    Route::get('showdetails/order/{id}', [OrderController::class, 'showDetailsOrder']);

    Route::apiResource('categories', CategoryController::class);
});

Route::post('register', [UserController::class, 'register']);

Route::post('login', [UserController::class, 'login']);

Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
