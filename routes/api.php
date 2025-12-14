<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KategoriLayananController;
use App\Http\Controllers\Api\LayananController;
use App\Http\Controllers\Api\BookingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});

// 🔹 KATEGORI LAYANAN ROUTES
Route::middleware('auth:sanctum')
    ->prefix('kategori-layanan')
    ->group(function () {
        Route::get('/', [KategoriLayananController::class, 'index']);
});

// 🔹 LAYANAN ROUTES
Route::middleware('auth:sanctum')
    ->prefix('layanan')
    ->group(function () {
    Route::get('/', [LayananController::class, 'index']);
    Route::get('/{id}', [LayananController::class, 'show']);
});
// 🔹 BOOKING ROUTES
Route::middleware('auth:sanctum')
    ->prefix('bookings')
    ->group(function () {
        Route::post('/', [BookingController::class, 'createBooking']);
        Route::get('/history', [BookingController::class, 'history']);
        Route::get('/check-status/{bookingId}', [BookingController::class, 'checkPaymentStatus']);
        Route::get('/check/{orderId}', [BookingController::class, 'checkStatus']);
        Route::get('/{id}', [BookingController::class, 'show']);
});

// MIDTRANS CALLBACK (without auth)
Route::post('/payment/callback', [BookingController::class, 'callback']);

