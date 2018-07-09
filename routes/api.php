<?php

use Illuminate\Http\Request;

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

Route::post('/user/register','UserController@register');


Route::post('/user/login','UserController@login');


Route::get('/user/info','UserController@userInfo');


Route::get('/message/send','UserController@sendMessage');