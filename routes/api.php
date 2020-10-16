<?php

use App\Http\Controllers\API\PizzaController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::middleware(['jwt.auth'])->group(function () {
        // Profile
        Route::patch('profile', [UserController::class, 'updateProfile']);
        Route::get('profile', [UserController::class, 'getProfile']);

        // pizza
        Route::post('pizza', [PizzaController::class, 'addPizza']);
        Route::delete('pizza/{id}',  [PizzaController::class, 'deletePizza']);

        // order
        Route::get('order/history', [OrderController::class, 'getOrderHistory']);
    });

    // Auth
    Route::post('auth/register', [UserController::class, 'register']);
    Route::post('auth/login', [UserController::class, 'login']);

    //Pizza
    Route::get('pizza', [PizzaController::class, 'getMenu']);
    Route::get('pizza/{id}',  [PizzaController::class, 'getPizza']);

    // Cart
    Route::get('cart/uniqueId', [CartController::class, 'createCart']);
    Route::post('cart/{pizza_id}', [CartController::class, 'addToCart']);
    Route::patch('cart/{cart_id}/{item_id}', [CartController::class, 'updateItemInCart']);
    Route::delete('cart/{cart_id}/{item_id}', [CartController::class, 'removeItemFromCart']);
    Route::delete('cart/{cart_id}', [CartController::class, 'emptyCart']);
    Route::get('cart/total/{cart_id}', [CartController::class, 'getCartTotalPrice']);
    Route::get('cart/{cart_id}', [CartController::class, 'viewCart']);

    // Order
    Route::post('order', [OrderController::class, 'createOrder']);
    Route::get('order/{id}', [OrderController::class, 'getOrderSummary']);
    Route::delete('order/{id}', [OrderController::class, 'cancelOrder']);
});
