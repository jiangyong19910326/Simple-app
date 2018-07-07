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

Route::get('/','StaticPagesController@home'); //主页
Route::get('/help','StaticPagesController@help')->name('help'); //帮助页
Route::get('/about','StaticPagesController@about')->name('about'); //关于

Route::get('/signup','UserController@create')->name('signup'); //注册页面
Route::resource('users','UserController'); //定义资源用户路由

/*
    登陆登出路由
*/
Route::get('login','SessionController@create')->name('login'); //登陆页面
Route::post('login','SessionController@store')->name('login'); //登陆操作 创建新会话
Route::delete('logout','SessionController@destroy')->name('logout'); //登出操作 销毁会话