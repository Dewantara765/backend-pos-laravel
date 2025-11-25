<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TransactionDetailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require a valid Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('/categories', CategoryController::class);

    Route::get('/categories/{category}/products', [CategoryController::class, 'showProducts']);

    Route::apiResource('/products', ProductController::class);

    Route::apiResource('/customers', CustomerController::class);

    Route::apiResource('/transactions', TransactionController::class);

    Route::get('/transactions/{transaction}/details', [TransactionController::class, 'showDetails']);

    Route::apiResource('/transaction-details', TransactionDetailController::class);

});




