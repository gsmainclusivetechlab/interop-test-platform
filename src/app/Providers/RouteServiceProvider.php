<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * @var string
     */
    protected $apiNamespace = 'App\Http\Controllers\Api';

    /**
     * @var string
     */
    public const HOME = '/';

    /**
     * @return void
     */
    public function boot()
    {
        parent::boot();
        $this->registerBindings();
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * @return void
     */
    protected function registerBindings()
    {
        Route::bind('userOnlyTrashed', function ($value) {
            return User::onlyTrashed()
                ->whereKey($value)
                ->firstOrFail();
        });
        Route::bind('userWithTrashed', function ($value) {
            return User::withTrashed()
                ->whereKey($value)
                ->firstOrFail();
        });
    }

    /**
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->name('api.')
            ->namespace($this->apiNamespace)
            ->group(base_path('routes/api.php'));
    }
}
