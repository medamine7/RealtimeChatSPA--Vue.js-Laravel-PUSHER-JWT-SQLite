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


Route::middleware(['jwt.auth'])->group(function () {

	Route::get('/user/info','UserController@userInfo');
	Route::post('/user/find','UserController@findUser');
	Route::post('/message/send','MessagesController@sendMessage');
	Route::get('/conversations','ConversationsController@getConversations');
	Route::post('/conversation/get','ConversationsController@getConversation');
	
});

