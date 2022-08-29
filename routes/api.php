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

// Route::get('/param/{cpf}/{password}', 'AuthController@register');

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login')->name('api.login');
Route::post('logout', 'AuthController@logout');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', 'AuthController@user')->name('api.user');
});
