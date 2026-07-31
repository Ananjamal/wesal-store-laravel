<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\LocaleCurrencyController;
use Inertia\Inertia;

Route::get('lang/{locale}', [LocaleCurrencyController::class, 'setLocale'])->name('set-locale');
Route::get('currency/{code}', [LocaleCurrencyController::class, 'setCurrency'])->name('set-currency');

Route::get('/', [App\Http\Controllers\Web\HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('profile', [AuthController::class, 'updateProfile']);
    Route::get('checkout', [\App\Http\Controllers\Web\OrderController::class, 'checkout'])->name('checkout');
    Route::post('checkout', [\App\Http\Controllers\Web\OrderController::class, 'store'])->name('checkout.store');
    Route::get('checkout/success/{order}', [\App\Http\Controllers\Web\OrderController::class, 'success'])->name('checkout.success');
    Route::get('checkout/invoice/{order}', [\App\Http\Controllers\Web\OrderController::class, 'invoice'])->name('checkout.invoice');
    Route::post('cart/sync', [\App\Http\Controllers\Api\CartController::class, 'sync'])->name('cart.sync');
});

Route::redirect('categories', 'products');
Route::get('products', [App\Http\Controllers\Web\ProductController::class, 'index'])->name('products.index');
Route::get('products/{slug}', [App\Http\Controllers\Web\ProductController::class, 'show'])->name('products.show');

Route::get('category/{slug}', [App\Http\Controllers\Web\CategoryController::class, 'show'])->name('category.show');

Route::get('blog', [App\Http\Controllers\Web\BlogController::class, 'index'])->name('blog.index');
Route::get('blog/{slug}', [App\Http\Controllers\Web\BlogController::class, 'show'])->name('blog.show');

Route::get('wishlist', fn() => Inertia::render('Wishlist'))->name('wishlist');
Route::get('cart', fn() => Inertia::render('Cart'))->name('cart');
Route::get('contact', fn() => Inertia::render('Contact'))->name('contact');
