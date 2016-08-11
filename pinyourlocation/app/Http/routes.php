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
use App\User;

Route::auth();
Route::get('/verify/{id}/{token}', 'Auth\NoGuardController@verify');
Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function() {
    Route::get('/', 'AdminController@index');
});
Route::get('/install',['middleware' => ['role:verified'], 'uses' => 'PinYourLocation\IndexController@install']);

//Route::group(['prefix' => 'pinyourlocation'], function() {
Route::get('/', 'PinYourLocation\IndexController@index');
Route::resource('location', 'PinYourLocation\LocationController');
Route::post('locations', 'PinYourLocation\LocationController@insert');

Route::get('user/{user}/location', 'UserController@location' );
Route::get('user/{user}', 'UserController@show' )->middleware('role:manager');
Route::get('user', 'UserController@index' )->middleware('auth');
Route::post('user/follow', 'UserController@follow' )->middleware('auth');
Route::post('user/push', 'UserController@push' )->middleware('auth');

Route::get('manager/', 'ManagerController@index' );
Route::get('profile/', 'HomeController@profile' );
Route::get('script/{token}', 'ScriptController@code' );

Route::get('authenticatebytoken/{token}', function ($token) {
    $user=User::where('token', $token)->firstOrFail();
    Auth::login($user);
    return redirect('/');
} );

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
