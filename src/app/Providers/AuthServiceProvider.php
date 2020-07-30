<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\Component;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Session;
use App\Models\ApiSpec;
use App\Models\TestCase;
use App\Models\TestStep;
use App\Models\UseCase;
use App\Models\User;
use App\Policies\ComponentPolicy;
use App\Policies\GroupMemberPolicy;
use App\Policies\GroupPolicy;
use App\Policies\SessionPolicy;
use App\Policies\ApiSpecPolicy;
use App\Policies\TestCasePolicy;
use App\Policies\TestStepPolicy;
use App\Policies\UseCasePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Group::class => GroupPolicy::class,
        GroupMember::class => GroupMemberPolicy::class,
        Session::class => SessionPolicy::class,
        Component::class => ComponentPolicy::class,
        ApiSpec::class => ApiSpecPolicy::class,
        TestCase::class => TestCasePolicy::class,
        TestStep::class => TestStepPolicy::class,
        UseCase::class => UseCasePolicy::class,
    ];

    /**
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
