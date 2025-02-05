<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuotationController;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/quotations', [QuotationController::class, 'showForm'])->name('quotations.form');
    Route::post('/quotations', [QuotationController::class, 'store'])->name('quotations.store');
});
