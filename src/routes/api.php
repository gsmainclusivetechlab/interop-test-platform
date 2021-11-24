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

Route::name('session.')
    ->prefix('session/{session:uuid}')
    ->group(function () {
        Route::get('test-cases', [SessionController::class, 'testCases'])
            ->name('test-cases');
        Route::post('test-cases/{testCase:uuid}/run', [SessionController::class, 'run'])
            ->name('test-cases.run');
        Route::get('test-runs/{testRun:uuid}/status', [SessionController::class, 'status'])
            ->name('test-runs.status');
        Route::post('test-runs/{testRun:uuid}/complete', [SessionController::class, 'complete'])
            ->name('test-runs.complete');
    });
