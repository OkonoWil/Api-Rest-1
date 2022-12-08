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
Route::post('/user/login', [UserController::class, 'login']);
Route::get('/cars', [CarsController::class, 'index']);
Route::post('/cars', [CarsController::class, 'store']);
Route::get('/cars/{cars}', [CarsController::class, 'show']);
Route::put('/cars/{cars}', [CarsController::class, 'update']);
Route::delete('/cars/{cars}', [CarsController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
