<?php

use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\Auth\RegisterUserController;
use App\Http\Controllers\Api\V1\Auth\UserSessionController;
use App\Http\Controllers\Api\V1\BannerController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CartItemController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\WishListItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/signup', [RegisterUserController::class, 'store']);
    Route::post('/login', [UserSessionController::class, 'store']);
    Route::post('/logout', [UserSessionController::class, 'destroy'])->middleware('auth:sanctum');
    Route::post('/verify-otp', [RegisterUserController::class, 'verifyOtp'])->middleware('throttle:3,1');
    Route::post('/resend-otp', [RegisterUserController::class, 'resendOtp']);
});

Route::get('user', [UserController::class, 'show'])->middleware('auth:sanctum');
Route::delete('user', [UserController::class, 'destroy'])->middleware('auth:sanctum');
Route::patch('user', [UserController::class, 'update'])->middleware('auth:sanctum');

Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

Route::apiResource('products', ProductController::class)->only(['index', 'show']);

Route::get('similar-products', [ProductController::class, 'getSimilarProducts']);

Route::apiResource('banners', BannerController::class);

Route::post('contacts', [ContactController::class, 'store']);

Route::get('wish-list-items', [WishListItemController::class, 'usersWishListItems'])->middleware('auth:sanctum');
Route::post('wish-list-items', [WishListItemController::class, 'store'])->middleware('auth:sanctum');
Route::delete('wish-list-items', [WishListItemController::class, 'destroy'])->middleware('auth:sanctum');

Route::post('cart-items', [CartItemController::class, 'store'])->middleware('auth:sanctum');
Route::delete('cart-items', [CartItemController::class, 'destroy'])->middleware('auth:sanctum');
Route::patch('cart-items', [CartItemController::class, 'update'])->middleware('auth:sanctum');

Route::get('user-cart', [CartController::class, 'show'])->middleware('auth:sanctum');

Route::post('order', [OrderController::class, 'store'])->middleware('auth:sanctum');
Route::get('user-orders', [OrderController::class, 'userOrders'])->middleware('auth:sanctum');
Route::patch('user-orders/{order_id}/cancel', [OrderController::class, 'cancelOrder'])->middleware('auth:sanctum');

Route::get('user-addresses', [AddressController::class, 'userAddresses'])->middleware('auth:sanctum');
Route::post('address', [AddressController::class, 'store'])->middleware('auth:sanctum');
Route::patch('user-addresses/{address_id}', [AddressController::class, 'update'])->middleware('auth:sanctum');
Route::delete('user-addresses/{address_id}', [AddressController::class, 'destroy'])->middleware('auth:sanctum');
