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

    // Đăng nhập.
    Route::post('auth/login', 'Admin\V1\AuthController@login');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        // Logout
        Route::post('auth/logout', 'Admin\V1\AuthController@logout');

        // Category
        Route::apiResource('categories', 'Admin\V1\CategoryController');
        // Category property
        Route::apiResource('category-properties', 'Admin\V1\CategoryPropertyController');
        // Location
        Route::apiResource('locations', 'Admin\V1\LocationController');
        // Post
        Route::apiResource('posts', 'Admin\V1\PostController');
        // Post Photo
        Route::apiResource('post-photos', 'Admin\V1\PostPhotoController');
        // Post property
        Route::apiResource('post-properties', 'Admin\V1\PostPropertyController');
    });
});
