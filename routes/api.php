<?php

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\SubcategoryController;
use App\Http\Controllers\Api\User\UserDashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

/* public authentication routes */
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

/* user dashboard api */
Route::get('/user-dashboard', [UserDashboardController::class, 'home'])->name('user-dashboard');
Route::get('/products-list', [UserDashboardController::class, 'products'])->name('products-list');

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

        /* sub categories routes */
        Route::get('/subcategories-list', [SubcategoryController::class, 'index'])->name('subcategories-list');
        Route::post('/store-subcategories', [SubcategoryController::class, 'store'])->name('store-subcategories');
        Route::put('/update-subcategories/{id}', [SubcategoryController::class, 'update'])->name('update-subcategories');
        Route::delete('/delete-subcategories/{id}', [SubcategoryController::class, 'destroy'])->name('delete-subcategories');

        /* product route */
        Route::get('/products-list', [ProductController::class, 'index'])->name('products-list');
        Route::get('/product-detail/{id}', [ProductController::class, 'show'])->name('product-detail');
        Route::post('/store-products', [ProductController::class, 'store'])->name('store-products');
        Route::post('/update-products/{id}', [ProductController::class, 'update'])->name('update-products');
        Route::delete('/delete-products/{id}', [ProductController::class, 'destroy'])->name('delete-products');
    });

    /* user routes */
    Route::middleware('role:user')->prefix('user')->group(function () {
        Route::get('/verify', fn () => ResponseHelper::success(['panel' => 'user'], 'User verified'));
    });
});