<?php

use App\Http\Controllers\API\PizzaController;
use App\Http\Controllers\API\UserController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
    // return $request->user();
// });

Route::group(['prefix' => 'v1'], function () {
    // Auth
    Route::post('auth/register', [UserController::class, 'register']);
    Route::post('auth/login', [UserController::class, 'login']);

    //Pizza
    Route::get('pizza', [PizzaController::class, 'getMenu']);
    Route::get('pizza/{id}',  [PizzaController::class, 'getPizza']);

    Route::middleware(['jwt.auth'])->group(function () {
        // Profile
        Route::patch('profile', [UserController::class, 'updateProfile']);
        Route::get('profile', [UserController::class, 'getProfile']);

        //pizza
        Route::post('pizza', [PizzaController::class, 'addPizza']);
        Route::delete('pizza/{id}',  [PizzaController::class, 'deletePizza']);
    });
});
