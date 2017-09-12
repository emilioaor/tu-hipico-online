<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Rutas sin authenticacion
Route::get('', ['uses' => 'DefaultController@showLoginForm', 'as' => 'index.login', 'middleware' => 'guest']);
Route::post('login', ['uses' => 'DefaultController@userAuthentication', 'as' => 'index.auth', 'middleware' => 'guest']);
Route::get('logout', ['uses' => 'DefaultController@logout', 'as' => 'index.logout']);

//Rutas con autenticacion USER
Route::group(['prefix' => 'user', 'middleware' => 'authUser'], function() {
    Route::get('', ['uses' => 'User\UserController@index', 'as' => 'user.index']);
    Route::resource('gains', 'User\GainController');
    Route::get('gains/{gain}/generateTicket', ['uses' => 'User\GainController@generateTicket', 'as' => 'gains.generateTicket']);
    Route::get('gains/{gain}/downloadTicket', ['uses' => 'User\GainController@downloadTicket', 'as' => 'gains.downloadTicket']);
    Route::post('gains/{gain}/print', ['uses' => 'User\GainController@printTicket', 'as' => 'gains.printTicket']);
    Route::get('gains/valueInTables/rest', ['uses' => 'User\GainController@valueInTablesRest', 'as' => 'gains.valueInTables.rest']);
    Route::get('config', ['uses' => 'User\ConfigController@config', 'as' => 'user.config']);
    Route::put('config/{user}/changePassword', ['uses' => 'User\ConfigController@changePassword', 'as' => 'user.config.changePassword']);
    Route::get('printSpooler', ['uses' => 'User\PrintSpoolerController@index', 'as' => 'user.printSpooler']);

    //Report
    Route::get('report/daily', ['uses' => 'User\ReportController@index', 'as' => 'user.report.daily']);
    Route::get('report/daily/generateReport', ['uses' => 'User\ReportController@generateDailyReport', 'as' => 'user.report.daily.generate']);
});

//Rutas con autenticacion ADMIN
Route::group(['prefix' => 'admin', 'middleware' => 'authAdmin'], function() {
    Route::get('', ['uses' => 'Admin\AdminController@index', 'as' => 'admin.index']);
    Route::resource('users', 'Admin\UserController');
    Route::get('users/{user}/changeStatus', ['uses' => 'Admin\UserController@changeStatus', 'as' => 'users.changeStatus']);
    Route::resource('horses', 'Admin\HorseController');
    Route::resource('runs', 'Admin\RunController');
    Route::resource('hippodromes', 'Admin\HippodromeController');
    Route::put('runs/{run}/changeStatus', ['uses' => 'Admin\RunController@changeStatus', 'as' => 'runs.changeStatus']);
    Route::put('runs/{run}/retireHorse/{horse}', ['uses' => 'Admin\RunController@retireHorse', 'as' => 'runs.retireHorse']);
    Route::get('runs/{run}/setGained/{horse}', ['uses' => 'Admin\RunController@setGained', 'as' => 'runs.setGained']);
    Route::get('runs/{run}/getTickets', ['uses' => 'Admin\RunController@ticketsForRunRest', 'as' => 'runs.tickets.rest']);
    Route::put('runs/{run}/updateTable', ['uses' => 'Admin\RunController@updateTable', 'as' => 'runs.updateTable']);
    Route::put('runs/{run}/updateDividend', ['uses' => 'Admin\RunController@updateDividend', 'as' => 'runs.updateDividend']);
});
