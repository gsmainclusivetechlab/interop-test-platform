<?php

namespace App\Providers;

use App\Mixins\PsrHttpRequestMixin;
use Illuminate\Http\Request;
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
        $this->registerHttpRequestMixins();
    }

    /**
     * @return void
     */
    protected function registerHttpRequestMixins()
    {
        Request::mixin(new PsrHttpRequestMixin);
    }
}
