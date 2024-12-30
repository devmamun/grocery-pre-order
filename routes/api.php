<?php

use Illuminate\Support\Facades\Route;
use Mamun\ShopPreOrder\Http\Controllers\ProductController;
use Mamun\ShopPreOrder\Http\Controllers\PreOrderController;
use Mamun\ShopPreOrder\Http\Middleware\RoleMiddleware;

// Public routes
Route::middleware('api')->prefix('api')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/pre-orders', [PreOrderController::class, 'store'])->middleware(['ratelimit:' . config('grocery.rate_limit_per_minute')]);
});

// Admin routes
Route::middleware(['api', 'auth:api', RoleMiddleware::class . ':admin'])->prefix('api/pre-orders')->group(function () {
    Route::controller(PreOrderController::class)->group(function () {
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
});

// Manager routes
Route::middleware(['api', 'auth:api', RoleMiddleware::class . ':manager'])->prefix('api/pre-orders')->group(function () {
    Route::get('/', [PreOrderController::class, 'index']);
});
