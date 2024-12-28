<?php

use Illuminate\Support\Facades\Route;
use Mamun\ShopPreOrder\Http\Controllers\ProductController;
use Mamun\ShopPreOrder\Http\Controllers\PreOrderController;
use Mamun\ShopPreOrder\Http\Middleware\RoleMiddleware;

Route::middleware('api')->prefix('api')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/pre-orders', [PreOrderController::class, 'store']);
});

// Admin routes
Route::middleware(['api', 'auth:api', RoleMiddleware::class . ':admin'])->prefix('api/pre-orders')->group(function () {
    Route::get('/', [PreOrderController::class, 'index']);
    // Route::post('/', [PreOrderController::class, 'store']);
    Route::get('/{id}', [PreOrderController::class, 'show']);
    Route::put('/{id}', [PreOrderController::class, 'update']);
    Route::delete('/{id}', [PreOrderController::class, 'destroy']);
});

// Manager routes
Route::middleware(['api', 'auth:api', RoleMiddleware::class . ':manager'])->prefix('api/pre-orders')->group(function () {
    Route::get('/', [PreOrderController::class, 'index']);
});
