<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\Component;
use App\Models\Scenario;
use App\Models\Session;
use App\Models\TestCase;
use App\Models\TestDatum;
use App\Models\TestStep;
use App\Models\UseCase;
use App\Models\User;
use App\Policies\ComponentPolicy;
use App\Policies\ScenarioPolicy;
use App\Policies\SessionPolicy;
use App\Policies\TestCasePolicy;
use App\Policies\TestDatumPolicy;
use App\Policies\TestStepPolicy;
use App\Policies\UseCasePolicy;
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
        Session::class => SessionPolicy::class,
        Scenario::class => ScenarioPolicy::class,
        Component::class => ComponentPolicy::class,
        TestCase::class => TestCasePolicy::class,
        TestDatum::class => TestDatumPolicy::class,
        TestStep::class => TestStepPolicy::class,
        UseCase::class => UseCasePolicy::class,
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
