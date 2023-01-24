<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\TransactionController;

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


Route::get('products', [ProductController::class, 'all']); // params 1 adalah nama routenya yaitu products, params 2 adalah nama controller dan methodnya (ini ada di folder app\Http\Controllers\API\ProductController.php)
Route::get('categories', [ProductCategoryController::class, 'all']); // params 1 adalah nama routenya yaitu categories, params 2 adalah nama controller dan methodnya (ini ada di folder app\Http\Controllers\API\ProductCategoryController.php
Route::post('register', [UserController::class, 'register']); // params 1 adalah nama routenya yaitu register, params 2 adalah nama controller dan methodnya (ini ada di folder app\Http\Controllers\API\UserController.php)
Route::post('login', [UserController::class, 'login']); // params 1 adalah nama routenya yaitu login, params 2 adalah nama controller dan methodnya (ini ada di folder app\Http\Controllers\API\UserController.php)

// middleware auth:sanctum untuk ngecek user sudah login atau blm
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'fetch']); // untuk mengambil data user
    Route::post('user', [UserController::class, 'updateProfile']); // untuk edit profile
    Route::post('logout', [UserController::class, 'logout']); // untuk logout

    Route::get('transactions', [TransactionController::class, 'all']); // untuk mengambil data transaksi
    Route::post('checkout', [TransactionController::class, 'checkout']); // untuk checkout
});