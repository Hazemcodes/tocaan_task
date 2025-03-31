<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Route::get('/protected-endpoint', [AuthController::class, 'protectedEndpoint']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('items', ItemController::class);
    Route::apiResource('orders', OrderController::class);
    
    Route::post('/payments', [PaymentController::class, 'processPayment']);
    Route::get('/payments', [PaymentController::class, 'getPayments']);
    Route::get('/payments/order/{order_id}', [PaymentController::class, 'getPaymentsByOrder']);
});
