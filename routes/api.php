<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;

// Auth routes (public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Products
    Route::get('/products',      [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    // Cart
    Route::get('/cart',         [CartController::class, 'index']);
    Route::post('/cart',        [CartController::class, 'store']);
    Route::put('/cart/{id}',    [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);

    // Orders
    Route::get('/orders',      [OrderController::class, 'index']);
    Route::post('/orders',     [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // Notifications
    Route::get('/notifications',              [NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read',    [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all',     [NotificationController::class, 'markAllAsRead']);

    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
});