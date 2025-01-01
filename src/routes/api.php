<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\MasterUser;
use App\Http\Middleware\Roquendo;
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
        Route::post('register', [UserController::class,'register']);
    });
    Route::group([
        'middleware' => 'auth:api',
    ], function ($router) {
        Route::group([
            'prefix' => 'users'
        ], function ($router) {

            Route::patch('update',[UserController::class,'update']);

            Route::group([
                'prefix' => 'admin',
                'middleware' => MasterUser::class
            ], function ($router) {
                Route::put('create',[UserController::class,'create']);
                Route::patch('update',[UserController::class,'updateOther']);
            });
        });
        Route::group([
            'prefix' => 'tournaments'
        ], function ($router) {

            Route::get('/',[TournamentController::class,'create']);

            Route::group([
                'prefix' => 'participants'
            ], function ($router) {
            
                // Route::post('upload',[FileController::class,'upload']);
            
            });
        
        });
        Route::group([
            'prefix' => 'participants'
        ], function ($router) {

            Route::get('/',[ParticipantController::class,'get']);
        
        });
    });
    
    
});
