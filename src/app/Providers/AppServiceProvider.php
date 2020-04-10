<?php declare(strict_types=1);

namespace App\Providers;

use App\Mixins\HttpClientMixin;
use App\Models\TestResult;
use App\Models\TestRun;
use App\Observers\TestResultObserver;
use App\Observers\TestRunObserver;
use Illuminate\Support\Facades\Http;
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
        $this->registerMixins();
        $this->registerObservers();
    }

    /**
     * @return void
     */
    protected function registerMixins()
    {
        Http::mixin(new HttpClientMixin());
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
