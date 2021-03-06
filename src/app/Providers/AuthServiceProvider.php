<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\{
    ApiSpec,
    AuditLog,
    Component,
    Group,
    GroupEnvironment,
    GroupUser,
    ImplicitSut,
    MessageLog,
    QuestionnaireSection,
    Session,
    TestCase,
    TestStep,
    UseCase,
    User
};
use App\Policies\{
    ApiSpecPolicy,
    AuditLogPolicy,
    ComponentPolicy,
    GroupEnvironmentPolicy,
    GroupUserPolicy,
    GroupPolicy,
    ImplicitSutPolicy,
    MessageLogPolicy,
    QuestionnairePolicy,
    SessionPolicy,
    TestCasePolicy,
    TestStepPolicy,
    UseCasePolicy,
    UserPolicy
};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Group::class => GroupPolicy::class,
        GroupUser::class => GroupUserPolicy::class,
        GroupEnvironment::class => GroupEnvironmentPolicy::class,
        Session::class => SessionPolicy::class,
        Component::class => ComponentPolicy::class,
        ApiSpec::class => ApiSpecPolicy::class,
        TestCase::class => TestCasePolicy::class,
        TestStep::class => TestStepPolicy::class,
        UseCase::class => UseCasePolicy::class,
        MessageLog::class => MessageLogPolicy::class,
        QuestionnaireSection::class => QuestionnairePolicy::class,
        AuditLog::class => AuditLogPolicy::class,
        ImplicitSut::class => ImplicitSutPolicy::class,
    ];

    /**
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
