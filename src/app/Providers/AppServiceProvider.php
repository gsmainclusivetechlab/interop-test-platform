<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\TestResult;
use App\Models\TestRun;
use App\Observers\TestResultObserver;
use App\Observers\TestRunObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->registerInertia();
        $this->registerObservers();
    }

    /**
     * @return void
     */
    protected function registerInertia()
    {
        Inertia::share([
            'app' => function () {
                return [
                    'debug' => env('APP_DEBUG'),
                ];
            },
            'auth' => function () {
                return [
                    'guest' => auth()->guest(),
                    'user' => !auth()->guest() ? [
                        'name' => auth()->user()->name,
                        'first_name' => auth()->user()->first_name,
                        'last_name' => auth()->user()->last_name,
                        'company' => auth()->user()->company,
                        'is_admin' => auth()->user()->isAdmin(),
                    ] : [],
                ];
            },
            'errors' => function () {
                return collect(session('errors', new ViewErrorBag())->getBag('default')->getMessages())
                    ->mapWithKeys(function ($value, $key) {
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
        ]);
    }

    /**
     * @return void
     */
    protected function registerObservers()
    {
        TestRun::observe(TestRunObserver::class);
        TestResult::observe(TestResultObserver::class);
    }
}
