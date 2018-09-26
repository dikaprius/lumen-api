<?php

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

$router->group(['prefix'=> 'api/v1'], function() use($router){
  $router->get('/products', 'ProductController@index');
  $router->post('/product', 'ProductController@create');
  $router->get('/product/{id}', 'ProductController@show');
  $router->post('/product/update', 'ProductController@update');
  $router->post('/product/delete', 'ProductController@destroy');
});
$router->get('/api/login', 'UserController@login');
$router->post('/api/login', 'UserController@authenticate');
$router->post('/api/register', 'UserController@register');

$router->group(['prefix' => 'api', 'middleware' =>'auth'], function() use($router){
  $router->post('todo', 'TodoController@store');
  $router->get('todo', 'TodoController@index');
  $router->get('todo/{id}', 'TodoController@show');
  $router->post('todo/update', 'TodoController@update');
  $router->delete('todo/{id}', 'TodoController@destroy');

});
