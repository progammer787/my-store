<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\BannerAdminController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\SettingAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\RoleAdminController;

// PUBLIC
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/shop/search', [ShopController::class, 'search'])->name('shop.search');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update', [CartController::class, 'update'])->name('update');
    Route::delete('/remove', [CartController::class, 'remove'])->name('remove');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/place', [CheckoutController::class, 'place'])->name('place');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});

// ADMIN
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware('permission:products')->group(function () {
            Route::resource('products', ProductAdminController::class);
            Route::post('products/{product}/toggle', [ProductAdminController::class, 'toggle'])->name('products.toggle');
        });

        Route::middleware('permission:categories')->group(function () {
            Route::resource('categories', CategoryAdminController::class);
        });

        Route::middleware('permission:banners')->group(function () {
            Route::resource('banners', BannerAdminController::class);
            Route::post('banners/reorder', [BannerAdminController::class, 'reorder'])->name('banners.reorder');
        });

        Route::middleware('permission:orders')->group(function () {
            Route::get('orders', [OrderAdminController::class, 'index'])->name('orders.index');
            Route::get('orders/{order}', [OrderAdminController::class, 'show'])->name('orders.show');
            Route::patch('orders/{order}/status', [OrderAdminController::class, 'updateStatus'])->name('orders.status');
        });

        Route::middleware('permission:settings')->group(function () {
            Route::get('settings', [SettingAdminController::class, 'index'])->name('settings.index');
            Route::post('settings', [SettingAdminController::class, 'save'])->name('settings.save');
        });

        Route::middleware('permission:users')->group(function () {
    Route::resource('users', UserAdminController::class);
    Route::post('users/{user}/toggle', [UserAdminController::class, 'toggleStatus'])->name('users.toggle');
});

        Route::resource('roles', RoleAdminController::class);
    });
});