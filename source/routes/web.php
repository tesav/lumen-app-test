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

$router->get('reset-password', ['as' => 'password.reset', 'uses' => 'ResetPasswordController@showResetForm']);
$router->post('reset-password', ['as' => 'password.reset',  'uses' => 'ResetPasswordController@reset']);

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/', function () use ($router) {
        return redirect()->route('register');
    });

    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('register', ['as' => 'register', 'uses' => 'UserController@register']);
        $router->post('sign-in', ['as' => 'signIn', 'uses' => 'UserController@signIn']);

        $router->post('recover-password', ['uses' => 'RequestPasswordController@sendResetLinkEmail']);
        $router->patch('recover-password', ['uses' => 'RequestPasswordController@sendResetLinkEmail']);
		
        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->get('companies', ['uses' => 'CompanyController@getList']);
            $router->post('companies', ['uses' => 'CompanyController@save']);
        });
    });
});
