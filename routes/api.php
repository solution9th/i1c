<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'middleware' => [
        'auth:api',
    ]
],function(){
    Route::get('geturl', 'ProjectController@getUrl');
    Route::get('user/info', 'UserController@info');
    Route::get('user/now', function (Request $request) {
        return $request->user();
    });
});

Route::group([
    'middleware' => [
        App\Http\Middleware\I1c::class,
        ]
    ],function() {
    Route::get('project/users', 'ProjectController@getUsers');
    Route::get('projects', 'ProjectController@listNoPage');
    Route::get('users', 'UserController@users');

    Route::post('client', 'OauthController@store');
    Route::post('verify', 'OauthController@verifiedPidSecret');
});

Route::get('captcha', 'CommonController@sendCaptcha');
Route::post('upload', 'CommonController@uploadImg');

//用户相关
Route::get('hasuser', 'UserController@existsUser');

Route::post('signup', 'RegisterController@register');
