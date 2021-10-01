<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\{
    ApiSpec,
    AuditLog,
    Component,
    Faq,
    Group,
    GroupEnvironment,
    GroupUser,
    ImplicitSut,
    MessageLog,
    QuestionnaireSection,
    Session,
    SimulatorPlugin,
    TestCase,
    TestStep,
    UseCase,
    User
};
use App\Policies\{
    ApiSpecPolicy,
    AuditLogPolicy,
    ComponentPolicy,
    FaqPolicy,
    GroupEnvironmentPolicy,
    GroupUserPolicy,
    GroupPolicy,
    ImplicitSutPolicy,
    MessageLogPolicy,
    QuestionnairePolicy,
    SessionPolicy,
    SimulatorPluginPolicy,
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
        SimulatorPlugin::class => SimulatorPluginPolicy::class,
        Faq::class => FaqPolicy::class,
    ];

    /**
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
