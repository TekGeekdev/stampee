<?php

use App\Controllers\user;
use App\Controllers\Article;
use App\Routes\Route;


Route::get('/', 'ArticleController@index');

Route::get('/user/create', 'userController@create');
Route::post('/user/create', 'userController@store');
Route::get('/user/show', 'userController@show');
Route::get('/user/edit', 'userController@edit');
Route::post('/user/edit', 'userController@update');

Route::get('/login', 'AuthController@login');
Route::post('/login', 'AuthController@store');
Route::get('/logout', 'AuthController@delete');


Route::dispatch();