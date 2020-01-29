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

Route::name('sessions.')->prefix('sessions')->namespace('Sessions')->group(function () {
    Route::get('', 'HomeController@index')->name('index');
    Route::name('register.')->prefix('register')->group(function () {
        Route::get('', 'RegisterController@createSelection')->name('selection.create');
        Route::post('', 'RegisterController@storeSelection')->name('selection.store');
        Route::get('configuration', 'RegisterController@createConfiguration')->name('configuration.create');
        Route::post('configuration', 'RegisterController@storeConfiguration')->name('configuration.store');
        Route::get('information', 'RegisterController@createInformation')->name('information.create');
        Route::post('information', 'RegisterController@storeInformation')->name('information.store');
    });
});

Route::name('admin.')->prefix('admin')->namespace('Admin')->group(function () {
    Route::resource('users', 'UserController', ['except' => ['show', 'create', 'store', 'edit', 'update']]);
    Route::name('users.')->prefix('users')->group(function () {
        Route::get('trash', 'UserController@trash')->name('trash');
        Route::post('{user}/restore', 'UserController@restore')->name('restore');
        Route::delete('{user}/force-destroy', 'UserController@forceDestroy')->name('force_destroy');
        Route::post('{user}/promote-admin', 'UserController@promoteAdmin')->name('promote_admin');
        Route::post('{user}/relegate-admin', 'UserController@relegateAdmin')->name('relegate_admin');
    });
    Route::resource('sessions', 'SessionController', ['except' => ['show', 'create', 'store', 'edit', 'update', 'destroy']]);
});
