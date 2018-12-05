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

// Authenticate account and get token
$router->group([
		'prefix' => 'api/v1'
	], function () use ($router) {
  	$router->post('auth/login',  ['uses' => 'AuthController@authenticate']);
});

// Application Route
$router->group([
		'prefix' => 'api/v1', 
		'middleware' => 'authApp'
	], function () use ($router) {
  	
  	// Get public info all user
  	$router->get('users',  ['uses' => 'UserController@showAllUsers']);
  	
  	// Register new user
  	$router->post('users', ['uses' => 'UserController@create']);
});

// User route
$router->group([
		'prefix' => 'api/v1', 
		'middleware' => 'authUser'
	], function () use ($router) {

  	// Get private detail info user
  	$router->get('users/{id}', ['uses' => 'UserController@showOneUser']);

  	$router->delete('users/{id}', ['uses' => 'UserController@delete']);

  	$router->put('users/{id}', ['uses' => 'UserController@update']);
});
