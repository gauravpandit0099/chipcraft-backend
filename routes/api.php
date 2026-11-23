<?php

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

/* public authentication routes */
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

/* protected authenticated routes */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile-details', [AuthController::class, 'me'])->name('profile-details');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /* admin routes */
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        
        /* get admin dashboard data */
        Route::get('/dashboard-data', [DashboardController::class, 'index'])->name('dashboard-data');

        /* cateory api */
        Route::get('/categories-list', [CategoryController::class, 'index'])->name('categories-list');
        Route::post('/store-categories', [CategoryController::class, 'store'])->name('store-categories');
        Route::put('/update-categories/{id}', [CategoryController::class, 'update'])->name('update-categories');
        Route::delete('/delete-categories/{id}', [CategoryController::class, 'destroy'])->name('delete-categories');
    });

    /* user routes */
    Route::middleware('role:user')->prefix('user')->group(function () {
        Route::get('/verify', fn () => ResponseHelper::success(['panel' => 'user'], 'User verified'));
    });
});