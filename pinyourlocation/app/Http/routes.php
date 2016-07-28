<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

//Route::get('/', 'HomeController@index');
Route::get('/verify/{id}/{token}', 'Auth\NoGuardController@verify');
Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function() {
    Route::get('/', 'AdminController@index');
});
Route::get('/install',['middleware' => ['role:verified'], 'uses' => 'HomeController@install']);



//Route::group(['prefix' => 'pinyourlocation'], function() {
    Route::get('/', 'PinYourLocation\IndexController@index');
    Route::resource('location', 'PinYourLocation\LocationController');
    Route::post('locations', 'PinYourLocation\LocationController@insert');
//});


/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'api', 'namespace' => 'API'], function () {
    Route::group(['prefix' => 'v1'], function () {
        require config('infyom.laravel_generator.path.api_routes');
    });
});


Route::resource('holidays', 'holidayController');