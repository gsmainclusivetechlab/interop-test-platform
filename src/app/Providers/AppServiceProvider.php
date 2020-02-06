<?php

namespace App\Providers;

use App\Mixins\PsrRequestMixin;
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
        $this->registerRequestMixins();
    }

    /**
     * @return void
     */
    protected function registerRequestMixins()
    {
        Request::mixin(new PsrRequestMixin);
    }
}
