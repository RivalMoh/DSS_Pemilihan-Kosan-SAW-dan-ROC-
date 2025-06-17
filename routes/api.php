<?php

use App\Http\Controllers\KosanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Public routes
Route::get('/kosan', [KosanController::class, 'index']);
Route::get('/kosan/{kosan}', [KosanController::class, 'show']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Recommendations
    Route::get('/recommendations', [KosanController::class, 'getRecommendations']);
    
    // Admin routes
    Route::middleware('admin')->group(function () {
        // Kosan management
        Route::apiResource('kosan', KosanController::class)->except(['index', 'show']);
    });
});

// Authentication routes (Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
