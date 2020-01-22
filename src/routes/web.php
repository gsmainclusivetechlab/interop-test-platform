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

Route::resource('sessions', 'SessionController', ['except' => ['show']]);

Route::name('settings.')->prefix('settings')->namespace('Settings')->group(function () {
    Route::resource('users', 'UserController', ['except' => ['show', 'create', 'store', 'edit', 'update']]);
    Route::get('users/trashed', 'UserController@trashed')->name('users.trashed');
    Route::post('users/{user}/restore', 'UserController@restore')->name('users.restore');
    Route::delete('users/{user}/force-destroy', 'UserController@forceDestroy')->name('users.force-destroy');
    Route::resource('sessions', 'SessionController', ['except' => ['show', 'create', 'store', 'edit', 'update', 'destroy']]);
});
