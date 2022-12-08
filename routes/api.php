<?php

use App\Http\Controllers\CarsController;
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


Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login'])->name("login");
Route::get('/cars', [CarsController::class, 'index']);
Route::get('/cars/{cars}', [CarsController::class, 'show']);


Route::group(
    ["middleware" => ["auth:sanctum"]],
    function () {
        Route::post('/cars', [CarsController::class, 'store']);
        Route::put('/cars/{cars}', [CarsController::class, 'update']);
        Route::delete('/cars/{cars}', [CarsController::class, 'destroy']);
    }
);
