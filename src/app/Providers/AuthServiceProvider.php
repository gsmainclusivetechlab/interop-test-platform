<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\Environment;
use App\Models\TestSession;
use App\Models\User;
use App\Policies\EnvironmentPolicy;
use App\Policies\TestSessionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Environment::class => EnvironmentPolicy::class,
        TestSession::class => TestSessionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
