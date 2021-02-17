<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\AuditLog;
use App\Models\Component;
use App\Models\Group;
use App\Models\QuestionnaireSection;
use App\Models\Session;
use App\Models\ApiSpec;
use App\Models\TestCase;
use App\Models\UseCase;
use App\Models\MessageLog;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;
use Inertia\Inertia;

class InertiaServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->registerVersion();
        $this->registerVariables();
    }

    /**
     * @return void
     */
    protected function registerVersion()
    {
        Inertia::version(function () {
            return md5_file(public_path('assets/mix-manifest.json'));
        });
    }

    /**
     * @return void
     */
    protected function registerVariables()
    {
        Inertia::share([
            'app' => function () {
                return [
                    'debug' => env('APP_DEBUG'),
                    'dark_mode' => request()->cookie('dark_mode'),
                    'locales' => [
                        'default' => config('app.locale'),
                        'selected' => app()->getLocale(),
                        'supported' => config('app.locales'),
                    ],
                    'cookies_accepted' => request()->cookie('cookies_accepted'),
                    'testing_url_http' => config('app.testing_url_http'),
                    'testing_url_https' => config('app.testing_url_https'),
                    'available_session_modes_count' => collect(
                        config('service_session.available_modes')
                    )
                        ->filter()
                        ->count(),
                ];
            },
            'auth' => function () {
                return [
                    'guest' => auth()->guest(),
                    'user' => !auth()->guest()
                        ? [
                            'name' => auth()->user()->name,
                            'email' => auth()->user()->email,
                            'first_name' => auth()->user()->first_name,
                            'last_name' => auth()->user()->last_name,
                            'company' => auth()->user()->company,
                            'is_admin' => auth()
                                ->user()
                                ->isAdmin(),
                            'groups' => auth()
                                ->user()
                                ->groups->map(function ($group) {
                                    return Arr::add(
                                        $group,
                                        'isAdmin',
                                        $group->hasAdminUser(auth()->user())
                                    );
                                }),
                            'can' => [
                                'users' => [
                                    'viewAny' => auth()
                                        ->user()
                                        ->can('viewAny', User::class),
                                ],
                                'groups' => [
                                    'viewAny' => auth()
                                        ->user()
                                        ->can('viewAny', Group::class),
                                    'create' => auth()
                                        ->user()
                                        ->can('create', Group::class),
                                ],
                                'sessions' => [
                                    'viewAny' => auth()
                                        ->user()
                                        ->can('viewAny', Session::class),
                                ],
                                'api_specs' => [
                                    'viewAny' => auth()
                                        ->user()
                                        ->can('viewAny', ApiSpec::class),
                                    'create' => auth()
                                        ->user()
                                        ->can('create', ApiSpec::class),
                                ],
                                'components' => [
                                    'viewAny' => auth()
                                        ->user()
                                        ->can('viewAny', Component::class),
                                    'create' => auth()
                                        ->user()
                                        ->can('create', Component::class),
                                ],
                                'use_cases' => [
                                    'viewAny' => auth()
                                        ->user()
                                        ->can('viewAny', UseCase::class),
                                    'create' => auth()
                                        ->user()
                                        ->can('create', UseCase::class),
                                ],
                                'test_cases' => [
                                    'viewAny' => auth()
                                        ->user()
                                        ->can('viewAny', TestCase::class),
                                    'create' => auth()
                                        ->user()
                                        ->can('create', TestCase::class),
                                ],
                                'message_log' => [
                                    'viewAny' => auth()
                                        ->user()
                                        ->can('viewAny', MessageLog::class),
                                ],
                                'questionnaire' => [
                                    'create' => auth()
                                        ->user()
                                        ->can(
                                            'create',
                                            QuestionnaireSection::class
                                        ),
                                ],
                                'audit_log' => [
                                    'viewAny' => auth()
                                        ->user()
                                        ->can('viewAny', AuditLog::class),
                                ],
                            ],
                        ]
                        : [],
                ];
            },
            'errors' => function () {
                return collect(
                    session('errors', new ViewErrorBag())
                        ->getBag('default')
                        ->getMessages()
                )->mapWithKeys(function ($value, $key) {
                    return [$key => implode(' ', $value)];
                });
            },
            'messages' => function () {
                return collect()
                    ->put('success', session('success'))
                    ->put('error', session('error'))
                    ->put('warning', session('warning'))
                    ->put('info', session('info'))
                    ->filter();
            },
            'enums' => [
                'user_roles' => User::getRoleNames(),
                'test_case_behaviors' => TestCase::getBehaviorNames(),
            ],
        ]);
    }
}
