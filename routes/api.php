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

/**
 * AUTH for Users
 */
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::get('logout', 'AuthController@logout');
Route::get('user', 'AuthController@getAuthUser');

Route::group(['middleware' => ['jwt.verify']], function() {
    /**
     * CRUD for Messages
     */
    Route::get('messages', 'HomeController@retrieveAllMessages');
    Route::get('messages/{id}', 'HomeController@retrieveMessage');
    Route::post('messages', 'HomeController@createMessage');
    Route::delete('messages/{id}', 'HomeController@deleteMessage');
    Route::put('messages/{id}', 'HomeController@updateMessage');
});


