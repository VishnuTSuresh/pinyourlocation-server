<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where all API routes are defined.
|
*/

Route::resource('holidays', 'holidayAPIController');
Route::post('setoffice', 'PinYourLocation\LocationController@store_office_via_api');
Route::post('scriptfinish', 'ScriptController@scriptfinish' );