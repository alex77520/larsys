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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**
 * 后台登录路由组
 */
Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {
    // 需要判断是否登录和验证权限的路由
    Route::group(['middleware' => ['auth.admin', 'auth.permission', 'admin.log']], function () {
        Route::get('/', 'IndexController@index');
        Route::get('/article', 'ArticleController@index');

        // User
        Route::get('/user', 'UserController@index');
        Route::get('/user/add', 'UserController@add');
        Route::get('/user/{user_id}/edit', 'UserController@edit');
        Route::get('/user/{user_id}/del', 'UserController@del');
        Route::get('/user/{user_id}/frozen', 'UserController@frozen');
        Route::get('/user/{user_id}/unfrozen', 'UserController@unfrozen');

        // Role
        Route::get('/role', 'RoleController@index');
        Route::get('/role/add', 'RoleController@add');
        Route::get('/role/{role_id}/edit', 'RoleController@edit');
        Route::get('/role/{role_id}/del', 'RoleController@del');
        Route::post('/role/allot/{role_id}', 'RoleController@allot');

        // Permission
        Route::get('/permission', 'PermissionController@index');
        Route::get('/permission/add', 'PermissionController@add');
        Route::get('/permission/{role_id}/edit', 'PermissionController@edit');
        Route::get('/permission/{role_id}/del', 'PermissionController@del');

        // Cate
        Route::get('/cate', 'CateController@index');
        Route::get('/cate/add', 'CateController@add');
        Route::get('/cate/{cate_id}/edit', 'CateController@edit');
        Route::get('/cate/{cate_id}/del', 'CateController@del');
    });

    // User
    Route::post('/user/doAdd', 'UserController@doAdd');
    Route::post('/user/doEdit/{user_id}', 'UserController@doEdit');

    // Role
    Route::post('/role/doAdd', 'RoleController@doAdd');
    Route::post('/role/doEdit/{role_id}', 'RoleController@doEdit');
    Route::get('/role/getPermissions/{role_id}', 'RoleController@getPermissions');

    // Permission
    Route::post('/permission/doAdd', 'PermissionController@doAdd');
    Route::post('/permission/doEdit/{permission_id}', 'PermissionController@doEdit');

    // Login|Logout
    Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'LoginController@login');
    Route::post('/logout', 'LoginController@logout');

    // Log
    Route::get('/log', 'IndexController@log');

    // Cate
    Route::post('/cate/doAdd', 'CateController@doAdd');
    Route::post('/cate/doEdit/{cate_id}', 'CateController@doEdit');

});


