<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\TestResult;
use App\Models\TestRun;
use App\Observers\TestResultObserver;
use App\Observers\TestRunObserver;
use Illuminate\Support\ServiceProvider;

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
        $this->registerObservers();
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
