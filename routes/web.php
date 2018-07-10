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

Route::get('signup/confirm/{token}','UserController@confirmEmail')->name('confirm_email');

//密码重置路由
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request'); // 密码重置视图
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');  // 重置密码发送到邮箱
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset'); // 获取重置密码的表单视图
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update'); // 重置密码操作