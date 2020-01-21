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

Route::get('/', 'HomeController@index')->name('home');
Route::redirect('/home', '/');

Route::resource('sessions', 'SessionController', ['except' => ['show']]);

Route::name('settings.')->prefix('settings')->namespace('Settings')->group(function () {
    Route::resource('users', 'UserController', ['except' => ['show']]);
});
