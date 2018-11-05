<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API $apis
|--------------------------------------------------------------------------
|
| Here is where you can register API $apis for your application. These
| $apis are loaded by the $apiServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//$api->middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//$api->group(['prefix'=>'auth','namespace' => 'Api\V1\Controllers'],function (){
//    $api->post('login', 'AuthController@login');
//});
//
//$api->group(['middleware' => 'jwt.auth','namespace' => 'Api\V1\Controllers'], function () {
//    $api->group(['prefix' => 'auth'],function (){
//        $api->get('user', 'AuthController@user');
//        $api->post('logout', 'AuthController@logout');
//    });
//    $api->group(['prefix' => 'metas'],function (){
//        $api->get('/','MetasController@index');
//        $api->post('/','MetasController@create');
//        $api->get('destroy/{id}','MetasController@destroy');
//    });
//});
//$api->group(['prefix'=>'auth','middleware' => 'jwt.refresh','namespace' => 'Api\V1\Controllers'], function(){
//    $api->get('refresh', 'AuthController@refresh');
//});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['prefix' => 'auth', 'namespace' => 'App\Api\v1\Controllers'], function ($api) {
        $api->post('login', 'AuthController@login');
    });

    $api->group(['middleware' => 'jwt.auth', 'namespace' => 'App\Api\v1\Controllers'], function ($api) {
        $api->group(['prefix' => 'auth'], function ($api){
        $api->get('user', 'AuthController@user');
            $api->post('logout', 'AuthController@logout');
        });
        $api->group(['prefix' => 'metas'], function ($api) {
            $api->get('/', 'MetasController@index');
            $api->post('/', 'MetasController@create');
            $api->get('destroy/{id}', 'MetasController@destroy');
            $api->get('restore/{id}', 'MetasController@restore');
            $api->get('delete/{id}', 'MetasController@delete');
            $api->post('update/{id}', 'MetasController@update');
        });
        $api->group(['prefix' => 'tags'], function ($api) {
            $api->get('/','TagsController@index');
        });
        $api->group(['prefix' => 'article'], function ($api) {
            $api->get('/', 'ArticleController@index');
            $api->post('/', 'ArticleController@create');
            $api->get('destroy/{id}', 'ArticleController@destroy');
            $api->get('restore/{id}', 'ArticleController@restore');
            $api->get('delete/{id}', 'ArticleController@delete');
            $api->post('update/{id}', 'ArticleController@update');
        });
        $api->group(['prefix' => 'links'],function($api){
            $api->get('/','LinksController@index');
            $api->post('/','LinksController@create');
            $api->get('delete/{id}','LinksController@delete');
            $api->post('update/{id}','LinksController@update');
            $api->post('upload','LinksController@upload');
        });
        $api->group(['prefix' => 'comment'],function($api){
            $api->get('/','CommentController@index');
            $api->post('/','CommentController@create');//回复评论
            $api->post('update/{id}','CommentController@update');
        });

        $api->group(['prefix' => 'blog'], function ($api) {
            $api->get('metas',function (){
                return \App\Api\v1\Model\Metas::all()->toTree();
            });
        });
    });
    $api->group(['prefix' => 'auth', 'middleware' => 'jwt.refresh', 'namespace' => 'App\Api\v1\Controllers'], function ($api) {
        $api->get('refresh', 'AuthController@refresh');
    });
});
