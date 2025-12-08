<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Controller agar lebih rapi
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\OrderController; 
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\SuperAdminController; 
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminOrderController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Bisa Diakses Siapa Saja)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');    

// PENTING: Gunakan ProductController yang sudah kita perbaiki
Route::get('/product/{id}', [ProductController::class, 'detail'])->name('product.detail');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'store'])->name('cart.add');
Route::post('/cart/add-ajax/{id}', [CartController::class, 'addToCartAjax'])->name('cart.add.ajax'); 
Route::get('/cart/delete/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

// Checkout & Payment
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{kode_order}', [CheckoutController::class, 'showPayment'])->name('payment.show');
Route::get('/payment-success', [CheckoutController::class, 'success'])->name('payment.success');

// Authentication
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/register', [RegisterController::class, 'register'])->name('register');


/*
|--------------------------------------------------------------------------
| 2. USER ROUTES (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/user/upload', [UserDashboardController::class, 'upload'])->name('user.upload');
    Route::post('/user/upload', [UserDashboardController::class, 'store'])->name('user.upload.store');
});


/*
|--------------------------------------------------------------------------
| 3. SUPER ADMIN ROUTES (Prefix: /super-admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('super-admin')->name('super_admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');

    // Manajemen Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Manajemen Products (Menggunakan ProductController yang kita edit tadi)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::post('/products/update', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Quick Action (Approve/Reject/Feature)
    Route::get('/products/status/{id}/{action}', [ProductController::class, 'updateStatus'])->name('products.status');

    // Manajemen Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/update', [OrderController::class, 'updateStatus'])->name('orders.update');
    Route::delete('/orders/delete/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Manajemen Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});


/*
|--------------------------------------------------------------------------
| 4. ADMIN ROUTES (Prefix: /admin)
|--------------------------------------------------------------------------
*/
// PERBAIKAN: Group ini dipisah dari Super Admin (sejajar, bukan di dalam)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Admin Users (Read Only / Status Update)
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/status/{id}/{status}', [AdminUserController::class, 'updateStatus'])->name('users.status');

    // Admin Products (Jika logicnya beda dengan SuperAdmin, pakai AdminProductController)
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::post('/products/store', [AdminProductController::class, 'store'])->name('products.store');
    Route::post('/products/update', [AdminProductController::class, 'update'])->name('products.update');

    // Admin Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/store', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/update', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/delete/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Admin Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/update', [AdminOrderController::class, 'updateStatus'])->name('orders.update');
});