<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.master');
});
//页面
Route::prefix('view')->group(function(){
    Route::get('agent', 'AgentController@blade');
    Route::get('user', 'UserController@blade');
    Route::get('brand', 'BrandController@blade');
    Route::get('product', 'ProductController@blade');
    Route::get('product_exchange', 'ProductExchangeController@blade');
});
//资源管理路由
Route::resource('agent', 'AgentController');
Route::resource('user', 'UserController');
Route::resource('brand', 'BrandController');
Route::resource('product', 'ProductController');
Route::resource('product_exchange', 'ProductExchangeController');