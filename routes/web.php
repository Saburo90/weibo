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

Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

// 用户注册及用户展示
Route::get('signup', 'UsersController@create')->name('signup');
Route::resource('users', 'UsersController');

// 登录和退出
Route::get('/login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');

// 注册邮件激活
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

// 密码重置与忘记密码
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// 微博操作
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);

// 用户关注列表
Route::get('users/{user}/followings', 'UsersController@followings')->name('users.followings');
// 用户粉丝列表
Route::get('users/{user}/followers', 'UsersController@followers')->name('users.followers');
