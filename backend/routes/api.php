<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayHereController;
use App\Http\Controllers\HeroSlideController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BankAccountController;
// ── Public: Auth ───────────────────────────────────────────────────────────
Route::post('/admin/login',  [AuthController::class, 'adminLogin']);

// ── Public: Catalogue ──────────────────────────────────────────────────────
Route::get('/categories',          [CategoryController::class, 'index']);
Route::get('/products',            [ProductController::class, 'index']);
Route::get('/products/popular',    [ProductController::class, 'popular']);
Route::get('/products/{slug}',     [ProductController::class, 'show']);
Route::post('/cart/validate',      [ProductController::class, 'validateCart']);


Route::get('/fix-order-images', [OrderController::class, 'fixOrderImages']);
Route::get('/hero-slides',         [HeroSlideController::class, 'index']);

// ── Public: Settings ──────────────────────────────────────────────────────
Route::get('/settings/delivery-charges', [SettingController::class, 'getDeliveryCharges']);
Route::get('/bank-accounts',       [BankAccountController::class, 'index']);

// ── Public: Checkout & PayHere IPN ────────────────────────────────────────
Route::post('/checkout/order',     [OrderController::class, 'placeOrder']);
Route::get('/track-orders',        [OrderController::class, 'trackOrders']);
Route::post('/payhere/ipn',        [PayHereController::class, 'handleIpn']);
Route::post('/payhere/hash',       [PayHereController::class, 'generateHash']);

// ── Protected: Admin ──────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/logout', [AuthController::class, 'adminLogout']);

    // ── ONLY ADMIN ──
    Route::middleware('role:admin')->group(function () {
        // User Management
        Route::get('/admin/users',               [UserController::class, 'index']);
        Route::post('/admin/users',              [UserController::class, 'store']);
        Route::put('/admin/users/{id}',          [UserController::class, 'update']);
        Route::delete('/admin/users/{id}',       [UserController::class, 'destroy']);
        
        // Activity Logs
        Route::get('/admin/activity-logs',       [\App\Http\Controllers\ActivityLogController::class, 'index']);

        // SMS & Settings
        Route::get('/admin/sms/settings',        [\App\Http\Controllers\SmsController::class, 'getSettings']);
        Route::post('/admin/sms/settings',       [\App\Http\Controllers\SmsController::class, 'updateSettings']);
        Route::post('/admin/settings/delivery-charges', [SettingController::class, 'saveDeliveryCharges']);
        Route::get('/admin/sms/logs',            [\App\Http\Controllers\SmsController::class, 'getLogs']);
        Route::get('/admin/sms/billing',         [\App\Http\Controllers\SmsController::class, 'getBillingSummary']);
        Route::post('/admin/sms/promotional',    [\App\Http\Controllers\SmsController::class, 'sendPromotional']);

        // Bank Accounts
        Route::get('/admin/bank-accounts',       [BankAccountController::class, 'adminIndex']);
        Route::post('/admin/bank-accounts',      [BankAccountController::class, 'store']);
        Route::put('/admin/bank-accounts/{id}',  [BankAccountController::class, 'update']);
        Route::delete('/admin/bank-accounts/{id}',[BankAccountController::class, 'destroy']);
        Route::put('/admin/bank-accounts/{id}/toggle',[BankAccountController::class, 'toggleActive']);
    });

    // ── ADMIN & INVENTORY ──
    Route::middleware('role:admin,inventory')->group(function () {
        // Categories CRUD
        Route::get('/admin/categories',          [CategoryController::class, 'adminIndex']);
        Route::post('/admin/categories',         [CategoryController::class, 'store']);
        Route::put('/admin/categories/{id}',     [CategoryController::class, 'update']);
        Route::delete('/admin/categories/{id}',  [CategoryController::class, 'destroy']);

        // Products CRUD
        Route::get('/admin/dashboard/products-stats', [ProductController::class, 'dashboardStats']);
        Route::get('/admin/products',            [ProductController::class, 'adminIndex']);
        Route::post('/admin/products',           [ProductController::class, 'store']);
        Route::post('/admin/products/{id}',      [ProductController::class, 'update']);
        Route::delete('/admin/products/{id}',    [ProductController::class, 'destroy']);

        // Hero Slides
        Route::get('/admin/hero-slides',         [HeroSlideController::class, 'adminIndex']);
        Route::post('/admin/hero-slides',        [HeroSlideController::class, 'store']);
        Route::put('/admin/hero-slides/{id}',    [HeroSlideController::class, 'update']);
        Route::put('/admin/hero-slides/{id}/toggle', [HeroSlideController::class, 'toggleActive']);
        Route::delete('/admin/hero-slides/{id}', [HeroSlideController::class, 'destroy']);
    });

    // ── ADMIN & SALES ──
    Route::middleware('role:admin,sales')->group(function () {
        // Orders
        Route::get('/admin/orders',              [OrderController::class, 'listOrders']);
        Route::get('/admin/orders/stats',        [OrderController::class, 'orderStats']);
        Route::put('/admin/orders/{id}/status',  [OrderController::class, 'updateOrderStatus']);
        Route::get('/admin/dashboard/stats',     [OrderController::class, 'dashboardStats']);
        Route::get('/admin/orders/{id}/view-slip',    [OrderController::class, 'viewSlip']);
        Route::get('/admin/orders/{id}/download-slip',[OrderController::class, 'downloadSlip']);
        Route::delete('/admin/orders/{id}',      [OrderController::class, 'destroy']);
    });
});
