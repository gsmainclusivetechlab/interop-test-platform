<?php

namespace App\Providers;

use App\Actors\ActorManager;
use App\Contracts\ActorFactory;
use Illuminate\Support\ServiceProvider;

class ActorServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ActorFactory::class, function ($app) {
            return new ActorManager($app);
        });
    }
}
