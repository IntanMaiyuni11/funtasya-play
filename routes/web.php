<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

// Frontend
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;

// Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ShippingCostController;

// Auth & Profile
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialiteController;

// API ALAMAT 
use App\Http\controllers\AddressController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// =======================
// FRONTEND (USER / PUBLIC)
// =======================

// Halaman Utama / Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Katalog Produk
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');

// Detail Produk
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// About Us (Halaman Statis)
Route::view('/about', 'pages.about-us')->name('about');

// Cart 
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove'); 

// Bagian Checkout
Route::middleware(['auth'])->group(function () {
    Route::post('/checkout/instant', [CheckoutController::class, 'instantBuy'])->name('checkout.instant');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/change-address', [CheckoutController::class, 'changeAddress'])->name('checkout.changeAddress');
    
});

// payment
Route::get('/payment/{order_id}', [PaymentController::class, 'show'])->name('payment.show');
Route::get('/order-success/{order_id}', [PaymentController::class, 'success'])->name('order.success');
Route::post('/get-shipping-cost', [PaymentController::class, 'getOngkir'])->name('get-shipping-cost');

// cek status (WAJIB)
Route::get('/payment/check/{order_code}', function ($order_code) {
    $order = \App\Models\Order::where('order_code', $order_code)->first();

    return response()->json([
        'status' => $order?->status
    ]);
});

// ORDER 
Route::middleware(['auth'])->group(function () {
    Route::get('/order/detail/{orderCode}', [OrderController::class, 'detail'])
        ->name('order.detail');
    
    // Optional: untuk testing sample order
    Route::get('/order/sample', [OrderController::class, 'createSampleOrder'])
        ->name('order.sample');
});


// SOCIAL LOGIN (Google/GitHub dsb)
// =======================

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect'])->name('social.login');
Route::get('/auth/callback/{provider}', [SocialiteController::class, 'callback']);


// =======================
// CUSTOMER (LOGIN USER)
// =======================

Route::middleware(['auth', 'role:customer'])->group(function () {
    
    Route::get('/my-orders', function () {
        return "Halaman Pesanan Customer";
    })->name('customer.orders');

   
});

// ======================
// API ALAMAT
//=======================
Route::get('/provinces', [AddressController::class, 'getProvinces']);
Route::get('/cities/{province_id}', [AddressController::class, 'getCities']); // ID ini akan masuk ke $province_code
Route::get('/districts/{city_id}', [AddressController::class, 'getDistricts']);
Route::get('/postalcodes', [AddressController::class, 'getPostalCodes']);

//======================
// ADDRESSES
//======================
Route::middleware(['auth'])->group(function () {
    // Address routes
    Route::get('/addresses/{id}/edit', [AddressController::class, 'edit'])->name('addresses.edit');  // ← Untuk GET (ambil data)
    Route::put('/addresses/{id}', [AddressController::class, 'update'])->name('addresses.update');   // ← Untuk PUT (update)
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy'); // ← Untuk DELETE (hapus)
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');         // ← Untuk POST (tambah)
});

// =======================
// ADMIN (STAFF) - Fokus Operasional Pesanan
// =======================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Manajemen Pesanan
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Perbaikan: Pastikan menggunakan AdminOrderController agar konsisten
    Route::patch('/orders/{id}/complete', [AdminOrderController::class, 'updateToCompleted'])->name('orders.complete');

    // Biaya Ongkir (Admin hanya bisa LIHAT)
    Route::get('/shipping-costs', [App\Http\Controllers\Admin\ShippingCostController::class, 'index'])->name('shipping.index');
});


// =======================
// SUPER ADMIN (OWNER) - Akses Penuh
// =======================
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard/data', [DashboardController::class, 'getData']);

    // CRUD Master Data
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);

    // Manajemen Pesanan (Sama dengan Admin)
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{id}/complete', [AdminOrderController::class, 'updateToCompleted'])->name('orders.complete');

    // Biaya Ongkir (Super Admin bisa CRUD / Edit)
    Route::resource('shipping-costs', App\Http\Controllers\Admin\ShippingCostController::class)->names([
        'index' => 'shipping.index'
    ]);
});


// =======================
// PROFILE (DEFAULT LARAVEL BREEZE)
// =======================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =======================
// AUTH ROUTES (LOGIN, REGISTER, LOGOUT)
// =======================

// File ini berisi route bawaan Breeze (Login, Register, dsb)
require __DIR__.'/auth.php';