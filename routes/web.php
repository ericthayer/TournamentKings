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

// home
Route::get('/', 'WelcomeController@index')->name('welcome');
Route::post('/', 'WelcomeController@store');
Route::get('/early-access/register', 'WelcomeController@show')->name('lead-register');
Route::get('/thankyou', 'WelcomeController@thanks');
Route::get('/tourneybot', 'WelcomeController@discordBot');

Route::get('/home', 'HomeController@show')->name('home');

// location autocomplete
Route::get('locationautocomplete', ['as'=>'autocomplete', 'uses'=>'LocationController@autocomplete']);

Auth::routes(['verify' => true]);

// customized auth showRegistrationForm()
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::get('/privacy', 'PrivacyController@show')->name('privacy');

Route::get('/complete-registration/{user}', 'Auth\RegisterUserController@edit')->name('complete-register.edit')->middleware('signed');
Route::post('/complete-registration/{user}', 'Auth\RegisterUserController@update')->name('complete-register.update');

// All authorized users
Route::middleware('auth')->group(function () {
    Route::resource('matches', 'MatchController');

    Route::name('matches.')->group(function () {
        Route::prefix('matches')->group(function () {
            Route::get('{match_id}/delete', 'MatchController@delete');
            Route::get('{match}/create', 'MatchController@create');

            Route::get('{match}/confirmresults', 'MatchController@confirmResults')->name('confirm.show');
            Route::post('{match}/confirmresults', 'MatchController@confirm')->name('confirm.post');
        });
    });

    Route::get('/match/{match}/details', 'MatchController@show');

    Route::name('register.')->group(function () {
        // player registering
        Route::get('tournaments/{tournament}/register', 'TournamentController@registerConfirm')->name('confirm');
        Route::post('tournaments/{tournament}/register', 'TournamentController@register')
            ->name('post');
    });

    Route::get('player/{player}/details', 'PlayerController@show');

    // show tournament delete confirm page
    Route::get('/tournaments/{tournament}/delete', 'TournamentController@delete');

    // tournaments
    Route::resource('tournaments', 'TournamentController');

    Route::name('balance.')->group(function () {
        Route::prefix('balance')->group(function () {
            Route::get('/', 'BalanceController@showBalance')->name('show');
            Route::get('deposit', 'BalanceController@deposit')->name('deposit.create');
            Route::post('deposit', 'BalanceController@processPayment')->name('deposit.post');
            Route::get('withdrawal', 'BalanceController@withdrawal')->name('withdrawal.create');
            Route::post('withdrawal', 'BalanceController@postWithdrawal')->name('withdrawal.post');
            Route::post(
                'deposit/tournament/{tournament}',
                'BalanceController@transferFundsToTournament'
            )->name('deposit.tournament');
        });
    });

    Route::name('verification.')->group(function () {
        Route::prefix('withdrawal-email')->group(function () {
            Route::get('resend/{userWithdrawal}', 'Auth\WithdrawalEmailVerificationController@resend')->name('withdrawal.resend');
            Route::get('verify', 'Auth\WithdrawalEmailVerificationController@show')->name('withdrawal.notice');
            Route::get('verify/{id}', 'Auth\WithdrawalEmailVerificationController@verify')->name('withdrawal.verify');
        });
    });
});
