<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*Route::group([
    'prefix' => 'v1',
], function (){
    Route::apiResources([
        'brands' => BrandController::class,
        'categories' => CategoryController::class,
        'files' => FileController::class,
        'posts' => PostController::class,
        'products' => ProductController::class,
        'promotions' => PromotionController::class,
        'orders' => OrderController::class,
        'order_statuses' => OrderStatusController::class,
        'payments' => PaymentController::class,
    ]);
});*/
Route::group([
    'prefix' => 'v1',
], function () {
    Route::controller(BrandController::class)->group(function () {
        Route::get('/brands', 'index')->name('brand.index');
        Route::get('/brand/{brand}', 'show')->name('brand.show');
        Route::middleware('api')->group(function () {
            Route::post('/brand/create', 'store')->name('brand.store');
            Route::put('/brand/{brand}', 'update')->name('brand.update');
            Route::delete('/brand/{brand}', 'destroy')->name('brand.destroy');
        });
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('category.index');
        Route::get('/category/{category}', 'show')->name('category.show');
        Route::middleware('api')->group(function () {
            Route::post('/category/create', 'store')->name('category.store');
            Route::put('/category/{category}', 'update')->name('category.update');
            Route::delete('/category/{category}', 'destroy')->name('category.destroy');
        });
    });

    Route::controller(FileController::class)->group(function () {
        Route::get('/file/{file}', 'show')->name('file.show');
        Route::middleware('auth')->post('/file/upload', 'store')->name('file.store');
    });

    Route::controller(PostController::class)->group(function () {
        Route::get('/main/blog', 'index')->name('post.index');
        Route::get('/main/blog/{post}', 'show')->name('post.show');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('product.index');
        Route::get('/product/{product}', 'show')->name('product.show');
        Route::middleware('api')->group(function () {
            Route::post('/product/create', 'store')->name('product.store');
            Route::put('/product/{product}', 'update')->name('product.update');
            Route::delete('/product/{product}', 'destroy')->name('product.destroy');
        });
    });

    Route::controller(PromotionController::class)->group(function () {
        Route::get('/main/promotions', 'index')->name('promotion.index');
    });

    Route::middleware('api')->controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('order.index');
        Route::get('/orders/shipment-locator', 'locator')->name('order.locator');
        Route::get('/orders/dashboard', 'dashboard')->name('order.dashboard');
        Route::get('/order/{uuid}/download', 'download')->name('order.download');
        Route::get('/order/{order}', 'show')->name('order.show');
        Route::post('/order/create', 'store')->name('order.store');
        Route::put('/order/{order}', 'update')->name('order.update');
        Route::delete('/order/{order}', 'destroy')->name('order.destroy');
    });

    Route::controller(OrderStatusController::class)->group(function () {
        Route::get('/order-statuses', 'index')->name('order-status.index');
        Route::get('/order-status/{orderStatus}', 'show')->name('order-status.show');
        Route::middleware('api')->group(function () {
            Route::post('/order-status/create', 'store')->name('order-status.store');
            Route::put('/order-status/{orderStatus}', 'update')->name('order-status.update');
            Route::delete('/order-status/{orderStatus}', 'destroy')->name('order-status.destroy');
        });
    });

    Route::middleware('api')->controller(PaymentController::class)->group(function () {
        Route::get('/payments', 'index')->name('payment.index');
        Route::get('/payment/{payment}', 'show')->name('payment.show');
        Route::post('/payment/create', 'store')->name('payment.store');
        Route::put('/payment/{payment}', 'update')->name('payment.update');
        Route::delete('/payment/{payment}', 'destroy')->name('payment.destroy');
    });

    Route::controller(UserController::class)->group(function () {
        Route::post('/admin/create', 'storeAdmin')->name('admin.create');
        Route::post('/user/create', 'storeUser')->name('user.create');
        Route::middleware('api')->group(function () {
            Route::get('/admin/user-listing', 'index')->name('admin.index');
            Route::delete('/admin/user-delete/{user}', 'destroy')->name('user.destroy');
            Route::post('/user', 'userData')->name('user.userData');
            Route::delete('/user', 'destroyMe')->name('user.destroyMe');
            Route::put('/user/edit', 'update')->name('user.update');
            Route::get('/user/orders', 'userOrders')->name('user.orders');
        });
    });

    Route::middleware('api')->controller(AuthController::class)->group(function () {
        Route::post('/admin/login', 'login')->name('admin.login');
        Route::get('/admin/logout', 'logout')->name('admin.logout');
        Route::post('/user/login', 'login')->name('user.login');
        Route::get('/user/logout', 'logout')->name('user.logout');
    });


    Route::controller(AuthController::class)->group(function () {
        Route::post('/user/forgot-password', 'forgotPass')->name('user.forgot');
        Route::put('/user/reset-password-token', 'resetPass')->name('user.reset');
    });

});
