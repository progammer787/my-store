<?php

use Illuminate\Support\Facades\Route;

// Shop Controllers
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\ReviewController;

// Admin Controllers
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\BannerAdminController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\SettingAdminController;
use App\Http\Controllers\Admin\ReviewAdminController;

// ──────────────────────────────────────────────────────────────
// PUBLIC ROUTES
// ──────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop',                        [ShopController::class, 'index'])->name('shop');
Route::get('/shop/category/{slug}',        [ShopController::class, 'category'])->name('shop.category');
Route::get('/shop/search',                 [ShopController::class, 'search'])->name('shop.search');

Route::get('/product/{slug}',              [ProductController::class, 'show'])->name('product.show');
Route::post('/product/{product}/review',   [ReviewController::class, 'store'])->name('review.store');

// Cart
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/',                        [CartController::class, 'index'])->name('index');
    Route::post('/add',                    [CartController::class, 'add'])->name('add');
    Route::patch('/update',                [CartController::class, 'update'])->name('update');
    Route::delete('/remove',               [CartController::class, 'remove'])->name('remove');
    Route::get('/count',                   [CartController::class, 'count'])->name('count'); // AJAX
});

// Checkout → WhatsApp
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/',                        [CheckoutController::class, 'index'])->name('index');
    Route::post('/place',                  [CheckoutController::class, 'place'])->name('place');
    Route::get('/success/{order}',         [CheckoutController::class, 'success'])->name('success');
});

// ──────────────────────────────────────────────────────────────
// ADMIN ROUTES
// ──────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (guest only)
    Route::middleware('guest')->group(function () {
        Route::get('/login',               [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login',              [AuthController::class, 'login']);
    });
    Route::post('/logout',                 [AuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('/',                    [DashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::resource('products',        ProductAdminController::class);
        Route::post('products/{product}/toggle', [ProductAdminController::class, 'toggle'])->name('products.toggle');

        // Categories
        Route::resource('categories',      CategoryAdminController::class);

        // Banners
        Route::resource('banners',         BannerAdminController::class);
        Route::post('banners/reorder',     [BannerAdminController::class, 'reorder'])->name('banners.reorder');

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/',                [OrderAdminController::class, 'index'])->name('index');
            Route::get('/{order}',         [OrderAdminController::class, 'show'])->name('show');
            Route::patch('/{order}/status',[OrderAdminController::class, 'updateStatus'])->name('status');
        });

      // Settings
          Route::get('settings',               [SettingAdminController::class, 'index'])->name('settings.index');
           Route::post('settings/save',         [SettingAdminController::class, 'updateBatch'])->name('settings.save');

        // Reviews
        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::get('/',                [ReviewAdminController::class, 'index'])->name('index');
            Route::post('/{review}/approve',[ReviewAdminController::class, 'approve'])->name('approve');
            Route::delete('/{review}',     [ReviewAdminController::class, 'destroy'])->name('destroy');
        });
        
    });
});