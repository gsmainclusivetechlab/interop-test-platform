<?php declare(strict_types=1);

namespace App\Providers;

use App\Mixins\PsrHttpRequestMixin;
use App\Mixins\PsrHttpResponseMixin;
use App\Models\TestResult;
use App\Observers\TestResultObserver;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMixins();
        TestResult::observe(TestResultObserver::class);
    }

    /**
     * @return void
     */
    protected function registerMixins()
    {
        Request::mixin(new PsrHttpRequestMixin);
        Response::mixin(new PsrHttpResponseMixin());
    }
}
