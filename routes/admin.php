<?php

use App\Http\Controllers\Api\V1\Admin\CategoryManagementController;
use App\Http\Controllers\Api\V1\Admin\ContactSubmissionController;
use App\Http\Controllers\Api\V1\Admin\CouponManagementController;
use App\Http\Controllers\Api\V1\Admin\OrderManagementController;
use App\Http\Controllers\Api\V1\Admin\ProductManagementController;
use App\Http\Controllers\Api\V1\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth:sanctum')->name('admin.')->group(function () {
    Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
    Route::patch('users/{user_id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('users/{user_id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::patch('users/{user_id}/block', [UserManagementController::class, 'block'])->name('users.block');
    Route::patch('users/{user_id}/unblock', [UserManagementController::class, 'unblock'])->name('users.unblock');
    Route::get('staff-list', [UserManagementController::class, 'staff'])->name('staff.index');
    Route::post('create-admin', [UserManagementController::class, 'createAdmin'])->name('users.create-admin');

    Route::post('categories', [CategoryManagementController::class, 'store'])->name('categories.store');
    Route::patch('categories/{category_id}', [CategoryManagementController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category_id}', [CategoryManagementController::class, 'destroy'])->name('categories.destroy');

    Route::post('products', [ProductManagementController::class, 'store'])->name('products.store');
    Route::put('products/{product_id}', [ProductManagementController::class, 'replace'])->name('products.replace');
    Route::patch('products/{product_id}', [ProductManagementController::class, 'update'])->name('products.update');
    Route::delete('products/{product_id}', [ProductManagementController::class, 'destroy'])->name('products.destroy');

    Route::get('contacts', [ContactSubmissionController::class, 'index'])->name('contacts.index');

    Route::get('orders', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::patch('orders/next-status', [OrderManagementController::class, 'advanceStatus'])->name('orders.advance-status');
    Route::patch('orders/{order_id}/cancel', [OrderManagementController::class, 'cancel'])->name('orders.cancel');

    Route::get('coupons', [CouponManagementController::class, 'index'])->name('coupons.index');
    Route::post('coupons', [CouponManagementController::class, 'store'])->name('coupons.store');
});
