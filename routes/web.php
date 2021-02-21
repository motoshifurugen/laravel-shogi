<?php

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
    return view('welcome');
});

Route::get('shogi', 'App\Http\Controllers\ShogiController@index');

Route::get('shogi/select/{piece}', 'App\Http\Controllers\ShogiController@select');

Route::post('shogi', 'App\Http\Controllers\ShogiController@index');
