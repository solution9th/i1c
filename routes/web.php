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

Route::group([
'middleware' => [
    'throttle:6000,1',
    \App\Http\Middleware\I1::class,
    ],
],
function () {

    Route::get('/', 'HomeController@index');

    Route::get('/home/{any?}', 'HomeController@index')->where('any', '.*');

    Route::get('users', 'UserController@list');
//    Route::get('user/info/{id}', 'UserController@info');
    Route::post('user/add', 'UserController@addUser');
    Route::get('user/now', 'UserController@nowAdmin');

    Route::get('user/export', 'UserController@export');

    //字段相关
    Route::get('attr', 'AttributeController@list');
    Route::post('attr', 'AttributeController@store');
    Route::put('attr/{id}', 'AttributeController@update');
    Route::delete('attr/{id}', 'AttributeController@destory');

    //项目相关
    Route::get('project', 'ProjectController@list');
    Route::post('project', 'ProjectController@createProject');
    Route::put('project/update', 'ProjectController@updateProject');
    Route::get('hasproject', 'ProjectController@existsProject');
    Route::get('project/brief', 'ProjectController@listNoPage');

    Route::post('project/create/register', 'ProjectController@createRegister');
    Route::put('project/update/register', 'ProjectController@createRegister');

    Route::post('project/create/login', 'ProjectController@createLogin');
    Route::put('project/update/login', 'ProjectController@createLogin');

    Route::get('project/regnull', 'ProjectController@getRegfieldNull');
    Route::get('project/loginnull', 'ProjectController@getLoginfieldNull');
    Route::get('project/info/{pid}', 'ProjectController@info');

    Route::get('project/users', 'ProjectController@getUserlist');

    //上传相关
    Route::post('upload/logo', 'CommonController@uploadLogo');
    Route::post('upload/protocol', 'CommonController@uploadProtocol');

    Route::get('export/excel', 'CommonController@exportExcel');
});

//登录注册相关
Route::get('{pid}/signup', 'RegisterController@showRegistrationForm');
Route::get('signupform', 'RegisterController@getAttributes');
Route::post('signup', 'RegisterController@register');
Route::get('{pid}/login', 'LoginController@showLoginForm')->name('login');
Route::post('login', 'LoginController@Login');
Route::get('loginform', 'LoginController@getAttributes');

Route::get('login/auth', 'LoginController@authRedirect');

Route::post('logout', 'LoginController@logout')->name('logout');
Route::get('export/byid/{pid}', 'CommonController@exportExcelById');
