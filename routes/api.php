<?php

use App\Http\Controllers\Api\V1\RegisterUserController;
use App\Http\Controllers\api\v1\UserSessionController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/signup', [RegisterUserController::class, 'store']);
    Route::post('/login', [UserSessionController::class, 'store']);
    Route::post('/logout', [UserSessionController::class, 'destroy'])->middleware('auth:sanctum');
});