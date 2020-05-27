<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register the API routes for your application as
| the routes are automatically authenticated using the API guard and
| loaded automatically by this application's RouteServiceProvider.
|
*/

Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::get('/matches', 'MatchController@upcoming');
    Route::get('/tournaments', 'TournamentController@fetch')->name('tournaments.fetch');
    Route::get('/settings/platform_types', 'PlatformTypeController@index');
    Route::put('/settings/player/{player}', 'PlayerController@update');
});

//
//Route::prepend('v1')->group(['middleware' => 'auth:api',], function() {
//    Route::get('/user/create', '');
//});

Route::prefix('v1')->namespace('Api\V1')->middleware('auth:api', 'throttle:60,1')->group(function () {
    // Controllers Within The "App\Http\Controllers\Api\v1" Namespace
    Route::apiResource('users', 'UserController');
});
