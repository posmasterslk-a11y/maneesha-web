<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayHereController;
use App\Http\Controllers\HeroSlideController;

// ── Public: Auth ───────────────────────────────────────────────────────────
Route::post('/admin/login',  [AuthController::class, 'adminLogin']);

// ── Public: Catalogue ──────────────────────────────────────────────────────
Route::get('/categories',          [CategoryController::class, 'index']);
Route::get('/products',            [ProductController::class, 'index']);
Route::get('/products/popular',    [ProductController::class, 'popular']);
Route::get('/products/{slug}',     [ProductController::class, 'show']);
Route::get('/hero-slides',         [HeroSlideController::class, 'index']);

// ── Public: Checkout & PayHere IPN ────────────────────────────────────────
Route::post('/checkout/order',     [OrderController::class, 'placeOrder']);
Route::get('/track-orders',        [OrderController::class, 'trackOrders']);
Route::get('/orders/{id}/view-slip',    [OrderController::class, 'viewSlip']);
Route::get('/orders/{id}/download-slip',[OrderController::class, 'downloadSlip']);
Route::post('/payhere/ipn',        [PayHereController::class, 'handleIpn']);
Route::post('/payhere/hash',       [PayHereController::class, 'generateHash']);

// ── Protected: Admin ──────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/logout', [AuthController::class, 'adminLogout']);

    // Categories CRUD
    Route::get('/admin/categories',          [CategoryController::class, 'adminIndex']);
    Route::post('/admin/categories',         [CategoryController::class, 'store']);
    Route::put('/admin/categories/{id}',     [CategoryController::class, 'update']);
    Route::delete('/admin/categories/{id}',  [CategoryController::class, 'destroy']);

    // Products CRUD
    Route::get('/admin/products',            [ProductController::class, 'adminIndex']);
    Route::post('/admin/products',           [ProductController::class, 'store']);
    Route::post('/admin/products/{id}',      [ProductController::class, 'update']); // POST for FormData (file upload)
    Route::delete('/admin/products/{id}',    [ProductController::class, 'destroy']);

    // Orders
    Route::get('/admin/orders',              [OrderController::class, 'listOrders']);
    Route::get('/admin/orders/stats',        [OrderController::class, 'orderStats']);
    Route::put('/admin/orders/{id}/status',  [OrderController::class, 'updateOrderStatus']);
    Route::get('/admin/dashboard/stats',     [OrderController::class, 'dashboardStats']);

    // Admin Hero Slides
    Route::get('/hero-slides',         [HeroSlideController::class, 'adminIndex']);
    Route::post('/hero-slides',        [HeroSlideController::class, 'store']);
    Route::put('/hero-slides/{id}/toggle', [HeroSlideController::class, 'toggleActive']);
    Route::delete('/hero-slides/{id}', [HeroSlideController::class, 'destroy']);
});
