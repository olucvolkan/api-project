<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuotationController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::apiResource('quotations', QuotationController::class);
});
