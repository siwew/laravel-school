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



Route::namespace('Api')->group(function () {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('register', 'AuthController@register');

    Route::group(['middleware' => 'api:refresh'], function () {

        Route::post('school/add', 'SchoolController@createSchool');

        Route::post('school/teacher', 'SchoolController@teachers');

        Route::post('school/student', 'SchoolController@students');

        Route::post('inviteTeacher', 'SchoolController@inviteTeacher');

        Route::post('follow/list', 'UserController@followList');

        Route::post('follow', 'UserController@follow');


    });

});


