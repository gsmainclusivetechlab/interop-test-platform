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

Route::resource('sessions.test-cases.test-data', 'Sessions\TestDatumController')->shallow();
//Route::get('sessions/{session}/test-data/{testCase}', 'Sessions\TestDatumController@show')->name('sessions.test-data.show');

/**
 * Sessions Routes
 */
Route::name('sessions.')->prefix('sessions')->namespace('Sessions')->group(function () {
    Route::get('/', 'OverviewController@index')->name('index');
    Route::get('{session}', 'OverviewController@show')->name('show');
    Route::delete('{session}/destroy', 'OverviewController@destroy')->name('destroy');
    Route::get('{session}/chart', 'OverviewController@showChartData')->name('chart');
    Route::get('{session}/test-cases/{testCase}', 'TestCaseController@show')->name('test_cases.show');
    Route::get('{session}/test-cases/{testCase}/flow', 'TestCaseController@flow')->name('test_cases.flow');
    Route::get('{session}/test-cases/{testCase}/results/{testRun}/{position?}', 'TestCaseController@results')->name('test_cases.results');
    Route::name('register.')->prefix('register')->group(function () {
        Route::get('selection', 'RegisterController@createSelection')->name('selection.create');
        Route::post('selection', 'RegisterController@storeSelection')->name('selection.store');
        Route::get('configuration', 'RegisterController@createConfiguration')->name('configuration.create');
        Route::post('configuration', 'RegisterController@storeConfiguration')->name('configuration.store');
        Route::get('information', 'RegisterController@createInformation')->name('information.create');
        Route::post('information', 'RegisterController@storeInformation')->name('information.store');
    });
});

/**
 * Settings Routes
 */
Route::name('settings.')->prefix('settings')->namespace('Settings')->group(function () {
    Route::get('profile', 'ProfileController@edit')->name('profile.edit');
    Route::post('profile', 'ProfileController@update')->name('profile.update');
    Route::get('password', 'PasswordController@edit')->name('password.edit');
    Route::post('password', 'PasswordController@update')->name('password.update');
});

/**
 * Testing Routes
 */
Route::name('testing.')->prefix('testing')->namespace('Testing')->group(function () {
    Route::any('{session:uuid}/{testCase:uuid}/run/{path?}', 'TestingController@run')->name('run')->where('path', '.*');
    Route::any('test@{uri}', 'TestingController@test')->name('test')->where('uri', '.*');
});

/**
 * Admin Routes
 */
Route::name('admin.')->prefix('admin')->namespace('Admin')->group(function () {
    Route::name('users.')->prefix('users')->group(function () {
        Route::get('/{trashed?}', 'UserController@index')->name('index')->where('trashed', 'trashed');
        Route::delete('{user}/destroy', 'UserController@destroy')->name('destroy');
        Route::post('{userOnlyTrashed}/restore', 'UserController@restore')->name('restore');
        Route::delete('{userWithTrashed}/force-destroy', 'UserController@forceDestroy')->name('force_destroy');
        Route::post('{user}/promote-admin', 'UserController@promoteAdmin')->name('promote_admin');
        Route::post('{user}/relegate-admin', 'UserController@relegateAdmin')->name('relegate_admin');
    });
    Route::name('sessions.')->prefix('sessions')->group(function () {
        Route::get('/', 'SessionController@index')->name('index');
    });
    Route::resource('scenarios', 'ScenarioController');
    Route::resource('scenarios.components', 'ComponentController')->shallow();
    Route::resource('scenarios.use-cases', 'UseCaseController')->shallow();
    Route::resource('scenarios.test-cases', 'TestCaseController')->shallow();
    Route::get('scenarios/{scenario}/test-cases/import', 'TestCaseController@showImportForm')->name('scenarios.test-cases.import');
    Route::post('scenarios/{scenario}/test-cases/import', 'TestCaseController@import')->name('scenarios.test-cases.import');
    Route::resource('test-cases.test-steps', 'TestStepController')->shallow();
});
