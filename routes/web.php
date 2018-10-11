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
    return redirect('/view/login');
});
//微信认证
Route::any('wechat', 'WeChatController@server');

Route::get('wechat/menu', 'WeChatController@wetChatMenu');

Route::middleware('login.check')->group(function(){
    //页面
    Route::prefix('view')->group(function(){
        Route::get('agent', 'AgentController@blade');
        Route::get('user', 'UserController@blade');
        Route::get('brand', 'BrandController@blade');
        Route::get('product', 'ProductController@blade');
        Route::get('product_exchange', 'ProductExchangeController@blade');
        Route::get('echart', 'ProductController@echartBlade');
        Route::get('login', 'UserController@loginBlade');
    });

    Route::prefix('admin')->group(function(){
        Route::post('login', 'UserController@login');
    });

    Route::prefix('statistics')->group(function(){
        Route::get('brand', 'ProductExchangeController@brandStatistics');       //品牌纬度统计
        Route::get('product', 'ProductExchangeController@productStatistics');   //商品纬度统计
        Route::get('user', 'ProductExchangeController@userStatistics');     //用户纬度统计
        Route::get('month/{month}', 'ProductExchangeController@monthStatistics');   //月度报表分析
    });

    //资源管理路由
    Route::resource('agent', 'AgentController');    //代理级别
    Route::resource('user', 'UserController');      //代理用户
    Route::resource('brand', 'BrandController');    //品牌
    Route::resource('product', 'ProductController');    //商品
    Route::resource('product_exchange', 'ProductExchangeController');   //账单
    Route::put('product/{product}/inbound', 'ProductController@inbound');   //增加库存
});
