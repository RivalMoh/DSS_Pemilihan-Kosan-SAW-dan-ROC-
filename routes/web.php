<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kosan/{id}', [HomeController::class, 'show'])->name('kosan.show');

// Test route for recommendation service
Route::get('/test/recommendation', [TestController::class, 'testRecommendation'])->name('test.recommendation');

// Kosan Management Routes
// Kosan Management Routes
Route::post('/kosan', [HomeController::class, 'store'])->name('kosan.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/kosan/create', [HomeController::class, 'create'])->name('kosan.create');
    Route::get('/kosan/{id}/edit', [HomeController::class, 'edit'])->name('kosan.edit');
    Route::put('/kosan/{id}', [HomeController::class, 'update'])->name('kosan.update');
    Route::delete('/kosan/{id}', [HomeController::class, 'destroy'])->name('kosan.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
   ->name('admin.')
   ->middleware(['auth', 'admin'])
   ->group(function () {
       // Include admin routes
       require __DIR__.'/admin.php';
   });

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // User Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    
    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
