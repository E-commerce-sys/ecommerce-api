<?php

use App\Http\Controllers\Api\V1\BannerController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RegisterUserController;
use App\Http\Controllers\Api\V1\UserSessionController;
use App\Http\Controllers\Api\V1\WishListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/signup', [RegisterUserController::class, 'store']);
    Route::post('/login', [UserSessionController::class, 'store']);
    Route::post('/logout', [UserSessionController::class, 'destroy'])->middleware('auth:sanctum');
    Route::post('/verify-otp', [RegisterUserController::class, 'verifyOtp'])->middleware(['auth:sanctum', 'throttle:3,1']);
    Route::post('/resend-otp', [RegisterUserController::class, 'resendOtp'])
    ->middleware('auth:sanctum');
});

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('banners', BannerController::class);
Route::apiResource('contacts', ContactController::class);
Route::apiResource('wishlists', WishListController::class);
