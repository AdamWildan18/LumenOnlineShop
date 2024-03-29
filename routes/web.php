<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

Route::group(['middleware' => ['auth']], function ($router) {
    $router->get('/products', 'ProductController@index');
    $router->post('/products', 'ProductController@store');
    $router->get('/product/{id}', 'ProductController@show');
    $router->put('/product/{id}', 'ProductController@update');
    $router->delete('/product/{id}', 'ProductController@destroy');
});

Route::group(['middleware' => ['auth']], function ($router) {
    $router->get('/orders', 'OrderController@index');
    $router->post('/orders', 'OrderController@store');
    $router->get('/order/{id}', 'OrderController@show');
    $router->put('/order/{id}', 'OrderController@update');
    $router->delete('/order/{id}', 'OrderController@destroy');
});

$router->group(['prefix' => 'auth'], function () use ($router){
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');

});
