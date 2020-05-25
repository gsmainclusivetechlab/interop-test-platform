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
Route::name('legal.')->prefix('legal')->group(function () {
    Route::post('cookies/accept', 'LegalController@acceptCookies')->name('cookies.accept');
});
Route::post('/dark-mode', 'DarkModeController')->name('dark-mode');

/**
 * Sessions Routes
 */
Route::name('sessions.')->prefix('sessions')->namespace('Sessions')->group(function () {
    Route::get('/', 'SessionController@index')->name('index');
    Route::get('{session}', 'SessionController@show')->name('show');
    Route::delete('{session}/destroy', 'SessionController@destroy')->name('destroy');
    Route::get('{session}/chart', 'SessionController@showChartData')->name('chart');
    Route::get('{session}/test-cases/{testCase}', 'TestCaseController@show')->name('test-cases.show');
    Route::get('{session}/test-cases/{testCase}/test-runs', 'TestRunController@index')->name('test-cases.test-runs.index');
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
Route::name('testing.')->prefix('testing')->namespace('Testing')->group(function () {
    Route::any('{session:uuid}/{testCase:uuid}/run/{path?}', 'RunController')
        ->name('run')->where('path', '.*');
    Route::any('{session:uuid}/{component:uuid}/{connection:uuid}/sut/{path?}', 'SutController')
        ->name('sut')->where('path', '.*');
    Route::any('{component:uuid}/{connection:uuid}/simulator/{path?}', 'SimulatorController')
        ->name('simulator')->where('path', '.*');
});

/**
 * Admin Routes
 */
Route::name('admin.')->prefix('admin')->namespace('Admin')->group(function () {
    Route::resource('users', 'UserController')->only(['destroy']);
    Route::name('users.')->prefix('users')->group(function () {
        Route::get('/{trash?}', 'UserController@index')->name('index')->where('trash', '(trash)');
        Route::post('{userOnlyTrashed}/restore', 'UserController@restore')->name('restore');
        Route::delete('{userWithTrashed}/force-destroy', 'UserController@forceDestroy')->name('force-destroy');
        Route::post('{user}/verify', 'UserController@verify')->name('verify');
        Route::put('{user}/promote-role/{role}', 'UserController@promoteRole')->name('promote-role');
    });
    Route::resource('sessions', 'SessionController')->only(['index']);
    Route::resource('api-specs', 'ApiSpecController')->only(['index', 'destroy']);
    Route::name('api-specs.')->prefix('api-specs')->group(function () {
        Route::get('import', 'ApiSpecController@showImportForm')->name('import');
        Route::post('import', 'ApiSpecController@import')->name('import.confirm');
    });
    Route::resource('use-cases', 'UseCaseController')->except(['show']);
    Route::resource('test-cases', 'TestCaseController')->only(['index', 'destroy']);
    Route::name('test-cases.')->prefix('test-cases')->group(function () {
        Route::get('import', 'TestCaseController@showImportForm')->name('import');
        Route::post('import', 'TestCaseController@import')->name('import.confirm');
        Route::put('{testCase}/toggle-public', 'TestCaseController@togglePublic')->name('toggle-public');
    });
});
