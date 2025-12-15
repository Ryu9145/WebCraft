<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// --- Controllers ---
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
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
use App\Http\Controllers\GoogleController;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;


/*
|--------------------------------------------------------------------------
| 1. PUBLIC & AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'detail'])->name('product.detail');

Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('user.password.update');
});

// Social Auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Auth Manual
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/register', [RegisterController::class, 'register'])->name('register');


/*
|--------------------------------------------------------------------------
| 2. EMAIL VERIFICATION ROUTES
|--------------------------------------------------------------------------
*/

// 1. Halaman Notice "Harap Verifikasi Email"
// (Hanya bisa diakses jika sudah login tapi belum verif)
Route::get('/email/verify', function () {
    return view('auth.verify'); 
})->middleware('auth')->name('verification.notice');


// 2. [LOGIC BARU] Menangani link dari email (Bisa diakses tanpa login)
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    // Cari user berdasarkan ID
    $user = \App\Models\User::find($id);

    // Jika user tidak ditemukan
    if (! $user) {
        return redirect()->route('login')->with('login_error', 'User tidak valid.');
    }

    // Cek apakah Hash URL valid (Keamanan)
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect()->route('login')->with('login_error', 'Link verifikasi tidak valid atau kedaluwarsa.');
    }

    // Proses Verifikasi
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    // Jika user sedang login (dari proses register), arahkan ke dashboard
    if (Auth::check()) {
        return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi!');
    }

    // Jika user tidak login, arahkan ke login dengan pesan sukses
    return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan Login.');

})->middleware(['signed'])->name('verification.verify');


// 3. Tombol "Kirim Ulang Email Verifikasi"
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi telah dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


/*
|--------------------------------------------------------------------------
| 3. SHOPPING CART ROUTES (Bisa Diakses Member)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'store'])->name('cart.add');
    Route::post('/cart/add-ajax/{id}', [CartController::class, 'addToCartAjax'])->name('cart.add.ajax'); 
    Route::get('/cart/delete/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    // Checkout & Payment
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/payment/{kode_order}', [CheckoutController::class, 'showPayment'])->name('payment.show');
    Route::get('/payment-success', [CheckoutController::class, 'success'])->name('payment.success');
});


/*
|--------------------------------------------------------------------------
| 4. USER DASHBOARD (Wajib Verified)
|--------------------------------------------------------------------------
| Middleware 'verified' memastikan hanya user dengan email terverifikasi
| yang bisa mengakses halaman ini.
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/user/upload', [UserDashboardController::class, 'upload'])->name('user.upload');
    Route::post('/user/upload', [UserDashboardController::class, 'store'])->name('user.upload.store');
});


/*
|--------------------------------------------------------------------------
| 5. SUPER ADMIN ROUTES (Prefix: /super-admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('super-admin')->name('super_admin.')->group(function () {
    
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::post('/products/update', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/status/{id}/{action}', [ProductController::class, 'updateStatus'])->name('products.status');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/update', [OrderController::class, 'updateStatus'])->name('orders.update');
    Route::delete('/orders/delete/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});


/*
|--------------------------------------------------------------------------
| 6. ADMIN ROUTES (Prefix: /admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/status/{id}/{status}', [AdminUserController::class, 'updateStatus'])->name('users.status');

    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::post('/products/store', [AdminProductController::class, 'store'])->name('products.store');
    Route::post('/products/update', [AdminProductController::class, 'update'])->name('products.update');

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/store', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/update', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/delete/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/update', [AdminOrderController::class, 'updateStatus'])->name('orders.update');
});

/*
|--------------------------------------------------------------------------
| PASSWORD RESET ROUTES (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    
    // 1. Tampilkan Form Lupa Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    // 2. Proses Kirim Link ke Email
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    // 3. Tampilkan Form Reset Password (Link dari Email)
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    // 4. Proses Update Password Baru
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});