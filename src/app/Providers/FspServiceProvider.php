<?php

namespace App\Providers;

use App\Fsp\FspManager;
use App\Contracts\FspFactory;
use Illuminate\Support\ServiceProvider;

class FspServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FspFactory::class, function ($app) {
            return new FspManager($app);
        });
    }
}
