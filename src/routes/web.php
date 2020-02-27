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


Auth::routes(['verify' => true]);

Route::redirect('/home', '/');
Route::get('/', 'HomeController@index')->name('home');

Route::name('settings.')->prefix('settings')->namespace('Settings')->group(function () {
    Route::get('profile', 'ProfileController@edit')->name('profile.edit');
    Route::post('profile', 'ProfileController@update')->name('profile.update');
    Route::get('password', 'PasswordController@edit')->name('password.edit');
    Route::post('password', 'PasswordController@update')->name('password.update');
});

//Route::resource('sessions', 'Sessions\HomeController', ['only' => ['index', 'show', 'destroy']]);
Route::name('sessions.')->prefix('sessions')->group(function () {
    Route::get('', 'Sessions\HomeController@index')->name('index');
    Route::get('trash', 'Sessions\HomeController@trash')->name('trash');
    Route::get('{session}', 'Sessions\HomeController@show')->name('show');
    Route::delete('{session}/destroy', 'Sessions\HomeController@destroy')->name('destroy');
    Route::post('{session}/restore', 'Sessions\HomeController@restore')->name('restore');
});
Route::resource('sessions.cases', 'Sessions\CaseController', ['only' => ['show']]);
//Route::resource('sessions.cases.runs', 'Sessions\RunController', ['only' => ['show']]);
Route::get('sessions/{session}/cases/{case}/runs/{run}/{position?}', 'Sessions\RunController@show')->name('sessions.cases.runs.show');
Route::name('sessions.')->prefix('sessions')->namespace('Sessions')->group(function () {
    Route::name('register.')->prefix('register')->group(function () {
        Route::get('selection', 'RegisterController@createSelection')->name('selection.create');
        Route::post('selection', 'RegisterController@storeSelection')->name('selection.store');
        Route::get('configuration', 'RegisterController@createConfiguration')->name('configuration.create');
        Route::post('configuration', 'RegisterController@storeConfiguration')->name('configuration.store');
        Route::get('information', 'RegisterController@createInformation')->name('information.create');
        Route::post('information', 'RegisterController@storeInformation')->name('information.store');
    });
});

Route::name('admin.')->prefix('admin')->namespace('Admin')->group(function () {
    Route::resource('users', 'UserController', ['only' => ['index', 'destroy']]);
    Route::name('users.')->prefix('users')->group(function () {
        Route::get('trash', 'UserController@trash')->name('trash');
        Route::post('{user}/restore', 'UserController@restore')->name('restore');
        Route::delete('{user}/force-destroy', 'UserController@forceDestroy')->name('force_destroy');
        Route::post('{user}/promote-admin', 'UserController@promoteAdmin')->name('promote_admin');
        Route::post('{user}/relegate-admin', 'UserController@relegateAdmin')->name('relegate_admin');
    });
    Route::resource('sessions', 'SessionController', ['only' => ['index', 'destroy']]);
    Route::name('sessions.')->prefix('sessions')->group(function () {
        Route::get('trash', 'SessionController@trash')->name('trash');
        Route::post('{session}/restore', 'SessionController@restore')->name('restore');
        Route::delete('{session}/force-destroy', 'SessionController@forceDestroy')->name('force_destroy');
    });
});

Route::name('testing.')->prefix('testing')->namespace('Testing')->group(function () {
    Route::any('{plan}/run/{path?}', 'RunController')->name('run')->where('path', '.*');
    Route::any('{specification}/test/{path?}', 'TestController')->name('test')->where('path', '.*');
});
