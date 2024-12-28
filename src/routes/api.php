<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
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

Route::group([
    'middleware' => 'api',
    'prefix'     => 'v1',
], function ($router) {
    Route::group([
        'prefix' => 'auth',
    ],function($router){
        Route::post('login', [AuthController::class,'login']);
    });
    Route::group([
        'middleware' => 'auth:api',
        'prefix' => 'test'
    ], function ($router) {
    
        // Route::post('upload',[FileController::class,'upload']);
    
    });
});