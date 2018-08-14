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


//后台
Route::get('/admin/login', 'Admin\LoginController@loginForm')->name('admin_login');
Route::post('/admin/login', 'Admin\LoginController@login');
Route::group(['middleware'=>'admin.auth'],function(){
    Route::get('/admin/logout', 'Admin\LoginController@logout');
    Route::get('/admin/index', 'Admin\IndexController@index');
    Route::get('/admin/home', 'Admin\IndexController@home');//用户首页

    Route::any('/user/list', 'Admin\UserController@list');//用户列表
    Route::post('/user/modify', 'Admin\UserController@modify');//修改用户状态
    Route::get('/user/log', 'Admin\UserController@log');//查看用户日志信息
    Route::get('/user/detail', 'Admin\UserController@detail');//查看用户详情信息
    Route::get('/user/addForm', 'Admin\UserController@addForm');//用户添加（表单）
    Route::post('/user/add', 'Admin\UserController@add');//用户添加
    Route::get('/user/export', 'Admin\UserController@export');//用户导出

    Route::any('/firm/list', 'Admin\FirmController@list');//用户列表
    Route::get('/firm/log', 'Admin\FirmController@log');//查看企业日志信息
    Route::get('/firm/detail', 'Admin\FirmController@detail');//查看企业详情信息
    Route::post('/firm/modify', 'Admin\FirmController@modify');//修改企业状态
});

Route::post('/uploadImg', 'UploadController@uploadImg');//图片上传

//用户
Route::get('/userRegister','UserLoginController@userRegister');
Route::post('/userRegister','UserLoginController@userRegister');
Route::get('/userLogin','UserLoginController@showLoginForm');
Route::post('/userLogin','UserLoginController@login');

//公司
Route::get('/firmRegister','FirmLoginController@firmRegister');
Route::post('/firmRegister','FirmLoginController@firmRegister');
Route::get('/firmLogin','FirmLoginController@showLoginForm');
Route::post('/firmLogin','FirmLoginController@login');


