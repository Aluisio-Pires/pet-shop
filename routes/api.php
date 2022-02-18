<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
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
], function (){
    Route::controller(BrandController::class)->group(function () {
        Route::get('/brands', 'index')->name('brand.index');
        Route::get('/brand/{brand}', 'show')->name('brand.show');
        Route::post('/brand/create', 'store')->name('brand.store');
        Route::put('/brand/{brand}', 'update')->name('brand.update');
        Route::delete('/brand/{brand}', 'destroy')->name('brand.destroy');
    });
    //user and admin controllers

});
