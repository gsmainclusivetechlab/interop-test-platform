<?php declare(strict_types=1);

namespace App\Providers;

use App\Services\Yaml\Yaml;
use Illuminate\Support\ServiceProvider;

class YamlServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind('yaml', function () {
            return new Yaml();
        });
    }
}
