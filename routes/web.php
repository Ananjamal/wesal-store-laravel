<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\LocaleCurrencyController;
use Inertia\Inertia;

Route::get('lang/{locale}', [LocaleCurrencyController::class, 'setLocale'])->name('set-locale');
Route::get('currency/{code}', [LocaleCurrencyController::class, 'setCurrency'])->name('set-currency');

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
