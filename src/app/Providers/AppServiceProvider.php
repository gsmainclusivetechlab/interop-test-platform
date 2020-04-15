<?php declare(strict_types=1);

namespace App\Providers;

use App\Http\Resources\UserResource;
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
//        Inertia::version(function () {
//            return md5_file(public_path('mix-manifest.json'));
//        });
        Inertia::share([
            'auth' => function () {
                return !auth()->guest() ? new UserResource(auth()->user()) : null;
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
