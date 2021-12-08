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

use App\Http\Controllers\Groups\GroupSimulatorPluginsController;
use App\Http\Controllers\Sessions\Register\{
    ConfigController,
    InfoController,
    QuestionnaireController,
    SutController,
    TypeController
};

Auth::routes(['verify' => true]);
Route::get('/', 'HomeController')->name('home');
Route::get('/tutorials', 'TutorialController')->name('tutorials');
Route::get('/faq', 'FaqController')->name('faq');
Route::name('legal.')
    ->prefix('legal')
    ->group(function () {
        Route::post('cookies/accept', 'LegalController@acceptCookies')->name(
            'cookies.accept'
        );
    });
Route::post('/dark-mode', 'DarkModeController')->name('dark-mode');
Route::post('/locale', 'LocaleController')->name('locale');

/**
 * Groups Routes
 */
Route::namespace('Groups')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::resource('groups', 'GroupController')->only(['index', 'show']);
        Route::put(
            'groups/{group}/toggle-default-session/{session}',
            'GroupController@toggleDefaultSession'
        )->name('groups.toggle-default-session');
        Route::resource('groups.users', 'GroupUserController')->only([
            'index',
            'create',
            'store',
            'destroy',
        ]);
        Route::get(
            'groups/{group}/users/candidates',
            'GroupUserController@candidates'
        )->name('groups.users.candidates');
        Route::put(
            'groups/{group}/users/{user}/toggle-admin',
            'GroupUserController@toggleAdmin'
        )->name('groups.users.toggle-admin');
        Route::resource(
            'groups.environments',
            'GroupEnvironmentController'
        )->except(['show']);
        Route::resource(
            'groups.certificates',
            'GroupCertificatesController'
        )->except(['show', 'edit']);
        Route::get('plugins/{simulator_plugin}/download', [
            GroupSimulatorPluginsController::class,
            'download',
        ])->name('plugins.download');
        Route::resource('groups.plugins', 'GroupSimulatorPluginsController')
            ->except(['show'])
            ->shallow();
        Route::resource(
            'groups.user-invitations',
            'GroupUserInvitationController'
        )->except(['show', 'edit']);
    });

/**
 * Sessions Routes
 */
Route::name('sessions.')
    ->prefix('sessions')
    ->namespace('Sessions')
    ->group(function () {
        Route::get('/', 'SessionController@index')->name('index');
        Route::get(
            'certificates-download',
            'CertificatesController@download'
        )->name('certificates.download');
        Route::post(
            'certificates-upload-csr',
            'CertificatesController@uploadCsr'
        )->name('certificates.upload-csr');
        Route::get(
            'certificates-download-csr',
            'CertificatesController@downloadCsr'
        )->name('certificates.download-csr');
        Route::get('{session}', 'SessionController@show')->name('show');
        Route::get('{session}/edit', 'SessionController@edit')->name('edit');
        Route::get('{session}/export', 'SessionController@export')->name(
            'export'
        );
        Route::post('{session}/complete', 'SessionController@complete')->name(
            'complete'
        );
        Route::put('{session}/update', 'SessionController@update')->name(
            'update'
        );
        Route::delete('{session}/destroy', 'SessionController@destroy')->name(
            'destroy'
        );
        Route::get('{session}/chart', 'SessionController@showChartData')->name(
            'chart'
        );
        Route::get(
            '{session}/message-log',
            '\App\Http\Controllers\MessageLogController@index'
        )->name('message-log.index');
        Route::put(
            '{session}/update-test-case/{testCaseToRemove}/{testCaseToAdd}',
            'SessionController@updateTestCase'
        )->name('update-test-case');
        Route::get(
            '{session}/test-cases/{testCase}',
            'TestCaseController@show'
        )->name('test-cases.show');
        Route::post(
            '{session}/test-cases/{testCase}/run',
            'TestCaseController@run'
        )->name('test-cases.run');
        Route::post(
            '{session}/test-cases/run-all',
            'TestCaseController@runAll'
        )->name('test-cases.run-all');
        Route::get(
            '{session}/test-cases/{testCase}/test-runs/{testRun}/{position?}',
            'TestRunController@show'
        )->name('test-cases.test-runs.show');
        Route::get(
            '{session}/test-cases/{testCase}/test-steps',
            'TestStepController@index'
        )->name('test-cases.test-steps.index');
        Route::get(
            '{session}/test-cases/{testCase}/test-steps/flow',
            'TestStepController@flow'
        )->name('test-cases.test-steps.flow');
        Route::name('register.')
            ->prefix('register')
            ->middleware(['auth', 'verified'])
            ->group(function () {
                Route::get('type', [TypeController::class, 'index'])->name(
                    'type'
                );
                Route::get('reset-test-cases', [
                    InfoController::class,
                    'resetTestCases',
                ])->name('reset-test-cases');
                Route::post('type/{type}', [
                    TypeController::class,
                    'store',
                ])->name('type.store');
                Route::get('sut', [SutController::class, 'index'])->name('sut');
                Route::post('sut', [SutController::class, 'store'])->name(
                    'sut.store'
                );
                Route::get('questionnaire/summary', [
                    QuestionnaireController::class,
                    'summary',
                ])->name('questionnaire.summary');
                Route::get('questionnaire/{section}', [
                    QuestionnaireController::class,
                    'index',
                ])->name('questionnaire');
                Route::post('questionaire/{section}', [
                    QuestionnaireController::class,
                    'store',
                ])->name('questionnaire.store');
                Route::get('info', [InfoController::class, 'index'])->name(
                    'info'
                );
                Route::post('info', [InfoController::class, 'store'])->name(
                    'info.store'
                );
                Route::get('config', [ConfigController::class, 'index'])->name(
                    'config'
                );
                Route::post('config', [ConfigController::class, 'store'])->name(
                    'config.store'
                );
                Route::get('environment-candidates', [
                    ConfigController::class,
                    'groupEnvironmentCandidates',
                ])->name('group-environment-candidates');
                Route::get('certificate-candidates', [
                    SutController::class,
                    'groupCertificateCandidates',
                ])->name('group-certificate-candidates');
            });
    });

/**
 * Settings Routes
 */
Route::name('settings.')
    ->prefix('settings')
    ->namespace('Settings')
    ->group(function () {
        Route::get('profile', 'ProfileController@showProfileForm')->name(
            'profile'
        );
        Route::post('profile', 'ProfileController@updateProfile')->name(
            'profile.update'
        );
        Route::get('password', 'PasswordController@showPasswordForm')->name(
            'password'
        );
        Route::post('password', 'PasswordController@updatePassword')->name(
            'password.update'
        );
        Route::resource('token', 'TokenController')->only(['index']);
        Route::post('token/generate', 'TokenController@generate')->name(
            'token.generate'
        );
    });

/**
 * Testing Routes
 */
Route::name('testing-insecure.')
    ->prefix('testing-insecure')
    ->namespace('Testing')
    ->group(function () {
        Route::any(
            '{componentSlug}/{connectionSlug}/simulator/{path?}',
            'SimulatorController'
        )
            ->name('simulator')
            ->where('path', '.*');
        Route::any(
            '{componentSlug}/{connectionSlug}/{session:uuid}/{path?}',
            'SutController@testingSession'
        )
            ->name('sut')
            ->where('path', '.*');
    });

Route::name('testing.')
    ->prefix('testing')
    ->namespace('Testing')
    ->group(function () {
        Route::any(
            '{componentSlug}/{connectionSlug}/simulator/{path?}',
            'SimulatorController'
        )
            ->name('simulator')
            ->where('path', '.*');
        Route::any(
            '{componentSlug}/{connectionSlug}/{session:uuid}/{path?}',
            'SutController@testingSession'
        )
            ->name('sut')
            ->where('path', '.*');
    });

Route::name('testing-insecure-group.')
    ->prefix('testing-insecure-group')
    ->namespace('Testing')
    ->group(function () {
        Route::any(
            '{componentSlug}/{connectionSlug}/{group}/{path?}',
            'SutController@testingGroup'
        )
            ->name('sut')
            ->where('path', '.*');
    });

Route::name('testing-group.')
    ->prefix('testing-group')
    ->namespace('Testing')
    ->group(function () {
        Route::any(
            '{componentSlug}/{connectionSlug}/{group}/{path?}',
            'SutController@testingGroup'
        )
            ->name('sut')
            ->where('path', '.*');
    });

/**
 * Admin Routes
 */
Route::name('admin.')
    ->prefix('admin')
    ->namespace('Admin')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::resource('users', 'UserController')->only(['destroy']);
        Route::name('users.')
            ->prefix('users')
            ->group(function () {
                Route::get('/{trash?}', 'UserController@index')
                    ->name('index')
                    ->where('trash', '(trash)');
                Route::post(
                    '{userOnlyTrashed}/restore',
                    'UserController@restore'
                )->name('restore');
                Route::delete(
                    '{userWithTrashed}/force-destroy',
                    'UserController@forceDestroy'
                )->name('force-destroy');
                Route::post('{user}/verify', 'UserController@verify')->name(
                    'verify'
                );
                Route::put(
                    '{user}/promote-role/{role}',
                    'UserController@promoteRole'
                )->name('promote-role');
            });
        Route::resource('groups', 'GroupController')->except(['show']);
        Route::get(
            'message-log',
            '\App\Http\Controllers\MessageLogController@admin'
        )->name('message-log');
        Route::get(
            'audit-log',
            '\App\Http\Controllers\AuditLogController@admin'
        )->name('audit-log');
        Route::resource('sessions', 'SessionController')->only(['index']);
        Route::name('compliance-sessions.')
            ->prefix('compliance-sessions')
            ->group(function () {
                Route::get(
                    '{status?}',
                    'ComplianceSessionController@index'
                )->name('index');
                Route::put(
                    '{session}',
                    'ComplianceSessionController@update'
                )->name('update');
            });
        Route::resource('api-specs', 'ApiSpecController')->only([
            'index',
            'edit',
            'destroy',
        ]);
        Route::name('api-specs.')
            ->prefix('api-specs')
            ->group(function () {
                Route::get('import', 'ApiSpecController@showImportForm')->name(
                    'import'
                );
                Route::post('import', 'ApiSpecController@import')->name(
                    'import.confirm'
                );
                Route::get(
                    '{apiSpec}/download',
                    'ApiSpecController@download'
                )->name('download');
                Route::post(
                    '{apiSpec}/update-spec',
                    'ApiSpecController@updateSpec'
                )->name('update-spec');
            });
        Route::name('faqs.')
            ->prefix('faqs')
            ->group(function () {
                Route::get('index', 'FaqController@index')->name(
                    'index'
                );
                Route::get('import', 'FaqController@showImportForm')->name(
                    'import'
                );
                Route::post('import', 'FaqController@import')->name(
                    'import.confirm'
                );
                Route::get('{faq}/export', 'FaqController@export')->name(
                    'export'
                );
                Route::put('{faq}/toggle-active', 'FaqController@toggleActive')->name(
                    'toggle-active'
                );
            });
        Route::resource('components', 'ComponentController')->except(['show']);
        Route::get(
            'components/connection-candidates',
            'ComponentController@connectionCandidates'
        )->name('components.connection-candidates');
        Route::resource('use-cases', 'UseCaseController')->except(['show']);
        Route::resource('implicit-suts', 'ImplicitSutController')->except([
            'show',
        ]);
        Route::namespace('TestCases')->group(function () {
            Route::resource('test-cases', 'TestCaseController')->except([
                'show',
                'edit',
                'update',
            ]);
            Route::name('test-cases.')
                ->prefix('test-cases')
                ->group(function () {
                    Route::get(
                        'import',
                        'TestCaseController@showImportForm'
                    )->name('import');
                    Route::post('import', 'TestCaseController@import')->name(
                        'import.confirm'
                    );
                    Route::get(
                        'batch-import',
                        'TestCaseController@showBatchImportForm'
                    )->name('batch-import');
                    Route::post('batch-import', 'TestCaseController@batchImport')->name(
                        'batch-import.confirm'
                    );
                    Route::get(
                        'group-candidates',
                        'TestCaseController@groupCandidates'
                    )->name('group-candidates');
                    Route::post(
                        'environment-candidates',
                        'TestCaseController@environmentCandidates'
                    )->name('environment-candidates');
                    Route::prefix('{testCase}')->group(function () {
                        Route::get(
                            'import',
                            'TestCaseController@showImportVersionForm'
                        )->name('import-version');
                        Route::get('export', 'TestCaseController@export')->name(
                            'export'
                        );
                        Route::get('flow', 'TestCaseController@flow')->name(
                            'flow'
                        );
                        Route::put(
                            'toggle-public',
                            'TestCaseController@togglePublic'
                        )->name('toggle-public');
                        Route::name('info.')
                            ->prefix('info')
                            ->group(function () {
                                Route::get(
                                    'show',
                                    'TestCaseInfoController@show'
                                )->name('show');
                                Route::get(
                                    'edit',
                                    'TestCaseInfoController@edit'
                                )->name('edit');
                                Route::put(
                                    'update',
                                    'TestCaseInfoController@update'
                                )->name('update');
                            });
                        Route::resource(
                            'components',
                            'ComponentsController'
                        )->except('show');
                        Route::resource(
                            'groups',
                            'TestCaseGroupController'
                        )->only(['index', 'store', 'destroy']);
                        Route::resource(
                            'test-steps',
                            'TestCaseTestStepController'
                        );
                        Route::resource(
                            'versions',
                            'TestCaseVersionController'
                        )->only(['index']);
                        Route::get(
                            'versions/publish',
                            'TestCaseVersionController@publish'
                        )->name('versions.publish');
                        Route::delete(
                            'versions/discard',
                            'TestCaseVersionController@discard'
                        )->name('versions.discard');
                    });
                });
        });
        Route::name('questionnaire.')
            ->prefix('questionnaire')
            ->group(function () {
                Route::get(
                    'import',
                    'QuestionnaireController@showImportForm'
                )->name('import');
                Route::post('import', 'QuestionnaireController@import')->name(
                    'import.confirm'
                );
            });
    });

Route::get('/health', function () {
    return 'ok';
});
