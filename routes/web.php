<?php

use App\Controllers\user;
use App\Controllers\Article;
use App\Routes\Route;


Route::get('/', "definir la page d'acceuil");

Route::get('/user/create', 'userController@create');
Route::post('/user/create', 'userController@store');
Route::get('/user/show', 'userController@show');
Route::get('/user/edit', 'userController@edit');
Route::post('/user/edit', 'userController@update');

Route::get('/stamp/create', 'StampController@create');
Route::post('/stamp/create', 'StampController@store');
Route::get('/stamp/create-img', 'StampController@create_stamp_img');
Route::post('/stamp/create-img', 'StampController@store_stamp_img');


Route::get('/login', 'AuthController@login');
Route::post('/login', 'AuthController@store');
Route::get('/logout', 'AuthController@delete');


Route::dispatch();