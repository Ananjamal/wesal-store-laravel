<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SocialAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public auth routes
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login',    [AuthController::class, 'login'])->name('login');

    // Google OAuth
    Route::get('google/redirect',  [SocialAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('google/callback',  [SocialAuthController::class, 'callback'])->name('google.callback');
});

// Public catalog routes
Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('categories',          [CategoryController::class, 'index'])->name('categories.index');
    Route::get('products',            [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{product}',  [ProductController::class, 'show'])->name('products.show');
    Route::get('search',              [\App\Http\Controllers\Api\SearchController::class, 'index'])->name('search');
});

// Protected routes (Sanctum token required)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('me',           [AuthController::class, 'me'])->name('me');
    
    // Customer Portal Endpoints
    Route::prefix('customer')->group(function () {
        Route::get('orders', [\App\Http\Controllers\Api\CustomerApiController::class, 'orders']);
        Route::get('orders/{order}', [\App\Http\Controllers\Api\CustomerApiController::class, 'orderDetails']);
        Route::get('wishlist', [\App\Http\Controllers\Api\CustomerApiController::class, 'wishlist']);
        Route::get('addresses', [\App\Http\Controllers\Api\CustomerApiController::class, 'addresses']);
        Route::post('addresses', [\App\Http\Controllers\Api\CustomerApiController::class, 'storeAddress']);
        Route::get('reviews', [\App\Http\Controllers\Api\CustomerApiController::class, 'reviews']);
    });
});

Route::post('/coupon/apply', [\App\Http\Controllers\Api\CouponController::class, 'apply']);
Route::post('/webhooks/payment/{gateway}', [\App\Http\Controllers\Api\PaymentWebhookController::class, 'handle']);

