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

Route::resource('sessions', 'SessionController', ['except' => ['show', 'create', 'store', 'edit', 'update', 'destroy']]);
Route::name('sessions.')->prefix('sessions')->group(function () {
    Route::name('register.')->prefix('register')->group(function () {
        Route::get('', 'SessionRegisterController@showSelections')->name('selection.index');
        Route::get('{component}', 'SessionRegisterController@createSelection')->name('selection.create');
        Route::post('{component}', 'SessionRegisterController@storeSelection')->name('selection.store');
        Route::get('{component}/forward-configuration', 'SessionRegisterController@createForwardConfiguration')->name('configuration.create-forward');
        Route::post('{component}/forward-configuration', 'SessionRegisterController@storeForwardConfiguration')->name('configuration.store-forward');
        Route::get('{component}/backward-configuration', 'SessionRegisterController@createBackwardConfiguration')->name('configuration.create-backward');
        Route::post('{component}/backward-configuration', 'SessionRegisterController@storeBackwardConfiguration')->name('configuration.store-backward');
        Route::get('{component}/information', 'SessionRegisterController@createInformation')->name('information.create');
        Route::post('{component}/information', 'SessionRegisterController@storeInformation')->name('information.store');
    });
});

Route::name('admin.')->prefix('admin')->namespace('Admin')->group(function () {
    Route::resource('users', 'UserController', ['except' => ['show', 'create', 'store', 'edit', 'update']]);
    Route::name('users.')->prefix('users')->group(function () {
        Route::get('trash', 'UserController@trash')->name('trash');
        Route::post('{user}/restore', 'UserController@restore')->name('restore');
        Route::delete('{user}/force-destroy', 'UserController@forceDestroy')->name('force-destroy');
        Route::post('{user}/promote-admin', 'UserController@promoteAdmin')->name('promote-admin');
        Route::post('{user}/relegate-admin', 'UserController@relegateAdmin')->name('relegate-admin');
    });
    Route::resource('sessions', 'SessionController', ['except' => ['show', 'create', 'store', 'edit', 'update', 'destroy']]);
});
