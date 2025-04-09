<?php

use App\Routes\Route;

Route::get('/', "AuctionController@home");

Route::get('/user/create', 'UserController@create');
Route::post('/user/create', 'UserController@store');
Route::get('/user/show', 'UserController@show');
Route::get('/user/edit', 'UserController@edit');
Route::post('/user/edit', 'UserController@update');

Route::get('/stamp/create', 'StampController@create');
Route::post('/stamp/create', 'StampController@store');
Route::get('/stamp/create-img', 'StampController@create_stamp_img');
Route::post('/stamp/create-img', 'StampController@store_stamp_img');
Route::get('/stamp/index', 'StampController@index');
Route::get('/stamp/edit', 'StampController@edit');
Route::post('/stamp/edit', 'StampController@update');
Route::post('/stamp/delete', 'StampController@delete');

Route::get('/auction', 'AuctionController@index');
Route::get('/auction/show', 'AuctionController@show');
Route::get('/auction/filter', 'AuctionController@filter');

Route::get('/auction/lord', 'AuctionController@indexLord');
Route::get('/auction/lord/filter', 'AuctionController@filterLord');

Route::get('/bid/store', 'BidController@store');
Route::get('/bid/store/show', 'BidController@storeShow');

Route::get('/login', 'AuthController@login');
Route::post('/login', 'AuthController@store');
Route::get('/logout', 'AuthController@delete');

Route::dispatch();
