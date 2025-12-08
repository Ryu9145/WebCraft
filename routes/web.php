<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\CartController;

use App\Http\Controllers\SuperAdminController; 
use App\Http\Controllers\UserController; 
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\OrderController; 
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [App\Http\Controllers\FrontProductController::class, 'show'])->name('product.detail');
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [App\Http\Controllers\CartController::class, 'store'])->name('cart.add'); // Form Biasa
Route::post('/cart/add-ajax/{id}', [App\Http\Controllers\CartController::class, 'addToCartAjax'])->name('cart.add.ajax'); // Ajax
Route::get('/cart/delete/{id}', [App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/checkout/store', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

Route::post('/checkout/store', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{kode_order}', [App\Http\Controllers\CheckoutController::class, 'showPayment'])->name('payment.show');
Route::get('/payment-success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('payment.success');

// Auth
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// AREA USER
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/user/upload', [UserDashboardController::class, 'upload'])->name('user.upload');
    Route::post('/user/upload', [UserDashboardController::class, 'store'])->name('user.upload.store');
});

// AREA SUPER ADMIN
Route::middleware(['auth'])->prefix('super-admin')->name('super_admin.')->group(function () {
    
    // Dashboard (Pakai SuperAdminController)
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');

    // Kelola Users (Pakai UserController yang sudah dipindah ke folder utama)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::post('/products/store', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::post('/products/update', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/delete/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/status/{id}/{action}', [App\Http\Controllers\ProductController::class, 'updateStatus'])->name('products.status');

    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/update', [App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.update');
    Route::delete('/orders/delete/{id}', [App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');

    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/store', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/update', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/delete/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/status/{id}/{status}', [AdminUserController::class, 'updateStatus'])->name('users.status');

    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::post('/products/store', [AdminProductController::class, 'store'])->name('products.store');
    Route::post('/products/update', [AdminProductController::class, 'update'])->name('products.update');

    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/store', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/update', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/delete/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/update', [AdminOrderController::class, 'updateStatus'])->name('orders.update');
});