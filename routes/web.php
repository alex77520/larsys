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
    Route::group(['middleware' => 'auth.admin'], function () {
        Route::get('/', 'IndexController@index');
        Route::get('/article', 'ArticleController@index');

        Route::get('/user', 'UserController@index');
        Route::get('/user/add', 'UserController@add');
        Route::get('/user/{user_id}/edit', 'UserController@edit');
        Route::get('/user/{user_id}/del', 'UserController@del');
        Route::get('/user/{user_id}/frozen', 'UserController@frozen');
        Route::get('/user/{user_id}/unfrozen', 'UserController@unfrozen');

        Route::get('/role', 'RoleController@index');
        Route::get('/role/add', 'RoleController@add');
        Route::get('/role/{role_id}/edit', 'RoleController@edit');
        Route::get('/role/{role_id}/del', 'RoleController@del');
        Route::post('/role/allot/{role_id}', 'RoleController@allot');
    });

    Route::post('/user/doAdd', 'UserController@doAdd');
    Route::post('/user/doEdit/{user_id}', 'UserController@doEdit');

    Route::post('/role/doAdd', 'RoleController@doAdd');
    Route::post('/role/doEdit/{role_id}', 'RoleController@doEdit');
    Route::get('/role/getPermissions/{user_id}', 'RoleController@getPermissions');

    Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'LoginController@login');
    Route::post('/logout', 'LoginController@logout');

});


