<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function () {
    // Đăng ký
    Route::post('auth/register', 'Api\V1\AuthController@register');
    // Đăng nhập email.
    Route::post('auth/login/email', 'Api\V1\AuthController@loginEmail');

    // đăng nhập social.
    Route::get('auth/{provider}/login', 'Api\V1\OauthController@social');
    Route::get('auth/{provider}/callback', 'Api\V1\OauthController@callback');
    Route::post('auth/{provider}/token', 'Api\V1\OauthController@token');

    // Location
    Route::get('locations', 'Api\V1\LocationController@index');
    Route::get('locations/{id}', 'Api\V1\LocationController@show');
    // Category
    Route::get('categories', 'Api\V1\CategoryController@index');
    // Category and property
    Route::get('categories/lists', 'Api\V1\CategoryController@getCategories');
    // Category Show 
    Route::get('categories/{id}', 'Api\V1\CategoryController@show');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        // Logout
        Route::post('auth/logout', 'Api\V1\AuthController@logout');
        // Upload photo
        Route::post('photos', 'Api\V1\PhotoController@store');
        Route::delete('photos/{id}', 'Api\V1\PhotoController@destroy');

        // User
        Route::put('users', 'Api\V1\UserController@update');
        Route::post('users/avatar', 'Api\V1\UserController@avatar');
    });
});
