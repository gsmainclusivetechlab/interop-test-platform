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
Route::get('/', 'HomeController')->name('home');
Route::get('/tutorials', 'TutorialController')->name('tutorials');

/**
 * Sessions Routes
 */
Route::name('sessions.')->prefix('sessions')->namespace('Sessions')->group(function () {
    Route::get('/', 'SessionController@index')->name('index');
    Route::get('{session}', 'SessionController@show')->name('show');
    Route::delete('{session}/destroy', 'SessionController@destroy')->name('destroy');
    Route::get('{session}/chart', 'SessionController@showChartData')->name('chart');
    Route::get('{session}/test-cases/{testCase}', 'TestCaseController@show')->name('test-cases.show');
    Route::get('{session}/test-cases/{testCase}/test-runs/{testRun}/{position?}', 'TestRunController@show')->name('test-cases.test-runs.show');
    Route::get('{session}/test-cases/{testCase}/test-steps', 'TestStepController@index')->name('test-cases.test-steps.index');
    Route::get('{session}/test-cases/{testCase}/test-steps/flow', 'TestStepController@flow')->name('test-cases.test-steps.flow');
    Route::name('register.')->prefix('register')->group(function () {
        Route::get('sut', 'RegisterController@showSutForm')->name('sut');
        Route::post('sut', 'RegisterController@storeSut')->name('sut.store');
        Route::get('info', 'RegisterController@showInfoForm')->name('info');
        Route::post('info', 'RegisterController@storeInfo')->name('info.store');
        Route::get('config', 'RegisterController@showConfigForm')->name('config');
        Route::post('config', 'RegisterController@storeConfig')->name('config.store');
    });
});

/**
 * Settings Routes
 */
Route::name('settings.')->prefix('settings')->namespace('Settings')->group(function () {
    Route::get('profile', 'ProfileController@showProfileForm')->name('profile');
    Route::post('profile', 'ProfileController@updateProfile')->name('profile.update');
    Route::get('password', 'PasswordController@showPasswordForm')->name('password');
    Route::post('password', 'PasswordController@updatePassword')->name('password.update');
});

/**
 * Testing Routes
 */
//Route::name('testing.')->prefix('testing')->namespace('Testing')->group(function () {
//    Route::any('{session:uuid}/{component:uuid}/run/{path?}', 'RunController')->name('run')->where('path', '.*');
//    Route::any('step/{path}', 'StepController')->name('step')->where('path', '.*');
//});

/**
 * Admin Routes
 */
Route::name('admin.')->prefix('admin')->namespace('Admin')->group(function () {
    Route::name('users.')->prefix('users')->group(function () {
        Route::get('/{trashed?}', 'UserController@index')->name('index')->where('trashed', 'trashed');
        Route::delete('{user}/destroy', 'UserController@destroy')->name('destroy');
        Route::post('{userOnlyTrashed}/restore', 'UserController@restore')->name('restore');
        Route::delete('{userWithTrashed}/force-destroy', 'UserController@forceDestroy')->name('force-destroy');
        Route::post('{user}/verify', 'UserController@verify')->name('verify');
        Route::post('{user}/promote-admin', 'UserController@promoteAdmin')->name('promote-admin');
        Route::post('{user}/relegate-admin', 'UserController@relegateAdmin')->name('relegate-admin');
    });
    Route::name('sessions.')->prefix('sessions')->group(function () {
        Route::get('/', 'SessionController@index')->name('index');
    });
    Route::resource('components', 'ComponentController')->shallow();
    Route::resource('use-cases', 'UseCaseController')->shallow()->except(['create']);
    Route::resource('test-cases', 'TestCaseController')->shallow();
    Route::get('test-cases/import', 'TestCaseController@showImportForm')->name('test-cases.import');
    Route::post('test-cases/import', 'TestCaseController@import')->name('test-cases.import.confirm');
    Route::resource('test-cases.test-steps', 'TestStepController')->shallow();
});
