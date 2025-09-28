<?php

use App\Http\Controllers\Api\MejaController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API Routes untuk sistem pemesanan warkop

Route::prefix('v1')->group(function () {
    
    // Routes untuk Meja
    Route::apiResource('meja', MejaController::class);
    Route::get('meja/qr/{qrCode}', [MejaController::class, 'getByQrCode']);
    
    // Routes untuk Menu
    Route::apiResource('menu', MenuController::class);
    Route::get('menu/kategori/{kategori}', [MenuController::class, 'getByCategory']);
    
    // Routes untuk Order
    Route::apiResource('orders', OrderController::class);
    Route::patch('orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::get('orders/meja/{mejaId}', [OrderController::class, 'getByMeja']);
    Route::get('orders/qr/{qrCode}', [OrderController::class, 'getByQrCode']);
});

// Route untuk mengecek API health
Route::get('health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'Warkop API is running',
        'timestamp' => now()->toISOString()
    ]);
});