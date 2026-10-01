<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/up', fn()=>response('OK'));
Route::get('/', [StoreController::class,'home'])->name('home');
Route::get('/shop', [StoreController::class,'shop'])->name('shop');
Route::get('/category/{category:slug}', [StoreController::class,'category'])->name('category');
Route::get('/product/{product:slug}', [StoreController::class,'product'])->name('product');

Route::get('/cart', [StoreController::class,'cart'])->name('cart');
Route::post('/cart/add/{product}', [StoreController::class,'addToCart'])->name('cart.add');
Route::patch('/cart', [StoreController::class,'updateCart'])->name('cart.update');
Route::delete('/cart/remove/{product}', [StoreController::class,'removeCart'])->name('cart.remove');
Route::post('/cart/coupon', [StoreController::class,'applyCoupon'])->name('cart.coupon');
Route::get('/checkout', [StoreController::class,'checkoutForm'])->name('checkout');
Route::post('/checkout', [StoreController::class,'checkout'])->name('checkout.submit');
Route::get('/order/success/{orderNumber}', [StoreController::class,'success'])->name('order.success');

Route::get('/admin/login', [AuthController::class,'loginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class,'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class,'logout'])->name('admin.logout');

Route::prefix('admin')->middleware('admin')->name('admin.')->group(function(){
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->only(['index','store','update','destroy']);
    Route::get('orders', [OrderController::class,'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class,'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class,'updateStatus'])->name('orders.status');
    Route::get('customers', [CustomerController::class,'index'])->name('customers.index');
    Route::resource('coupons', CouponController::class)->only(['index','store','update','destroy']);
    Route::resource('banners', BannerController::class)->only(['index','store','update','destroy']);
    Route::get('settings', [SettingController::class,'index'])->name('settings.index');
    Route::patch('settings', [SettingController::class,'update'])->name('settings.update');
});
