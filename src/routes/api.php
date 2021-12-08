<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\TestRunController;

Route::name('sessions.')
    ->prefix('sessions/{session:uuid}')
    ->group(function () {
        Route::get('test-cases', [SessionController::class, 'testCases'])
            ->name('test-cases');
        Route::post('test-cases/{testCase:uuid}/run', [SessionController::class, 'run'])
            ->name('test-cases.run');
    });
Route::name('test-runs.')
    ->prefix('test-runs/{testRun:uuid}')
    ->group(function () {
        Route::get('status', [TestRunController::class, 'status'])
            ->name('status');
        Route::post('complete', [TestRunController::class, 'complete'])
            ->name('complete');
    });
