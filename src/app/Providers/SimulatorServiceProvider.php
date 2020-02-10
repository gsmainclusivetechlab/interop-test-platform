<?php

namespace App\Providers;

use App\Simulators\SimulatorManager;
use App\Contracts\SimulatorFactory;
use Illuminate\Support\ServiceProvider;

class SimulatorServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SimulatorFactory::class, function ($app) {
            return new SimulatorManager($app);
        });
    }
}
