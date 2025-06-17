<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

// Admin Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Weight Settings
Route::prefix('settings')->name('admin.settings.')->group(function () {
    // Weight Settings
    Route::get('/weights', [AdminController::class, 'weightSettings'])->name('weights');
    Route::post('/weights/update', [AdminController::class, 'updateWeights'])->name('weights.update');
    
    // Attribute Ranges
    Route::get('/attribute-ranges', [AdminController::class, 'attributeRanges'])->name('attribute-ranges');
    Route::put('/attribute-ranges/{id}', [AdminController::class, 'updateAttributeRange'])->name('attribute-ranges.update');
});
