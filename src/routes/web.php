<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return json_encode([
        'app_name' => env('APP_NAME'),
        'app_url'  => env("APP_URL"),
        'app_env'  => env("APP_ENV"),
        'time'     => time(),
        'date'     => date('Y-m-d H:i:s'),
    ]);
});
