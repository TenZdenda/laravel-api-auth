<?php

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

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

Route::post('/create', [\App\Http\Controllers\ResetPasswordController::class, 'create']);
Route::get('/find/{token}', [\App\Http\Controllers\ResetPasswordController::class, 'find']);
Route::post('/reset', [\App\Http\Controllers\ResetPasswordController::class, 'reset']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('users', [\App\Http\Controllers\UserController::class, 'index']);
    Route::get('users/{id}', [\App\Http\Controllers\UserController::class, 'show']);

    Route::post('users', [\App\Http\Controllers\UserController::class, 'store']);
    Route::post('users/update/{id}', [\App\Http\Controllers\UserController::class, 'update']);
    Route::post('users/delete/{id}', [\App\Http\Controllers\UserController::class, 'destroy']);

});
