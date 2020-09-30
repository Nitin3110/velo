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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');
Route::get('/all-properties', [\App\Http\Controllers\Api\PropertiesController::class, 'getAllProperties'])->middleware('auth:api');
Route::get('/new-router', [\App\Http\Controllers\Api\PropertiesController::class, 'newRouter'])->middleware('auth:api');
Route::post('/update-router', [\App\Http\Controllers\Api\PropertiesController::class, 'updateRouter'])->middleware('auth:api');
Route::post('/delete-router', [\App\Http\Controllers\Api\PropertiesController::class, 'deleteRouter'])->middleware('auth:api');

