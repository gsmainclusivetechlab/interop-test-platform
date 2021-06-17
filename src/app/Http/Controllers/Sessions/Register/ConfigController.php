<?php

namespace App\Http\Controllers\Sessions\Register;

use App\Http\Controllers\Sessions\Traits\WithSutUrls;
use App\Http\Middleware\EnsureSessionIsPresent;
use App\Enums\{AuditActionEnum, AuditTypeEnum};
use App\Http\Controllers\Controller;
use Auth;
use App\Http\Controllers\Sessions\Register\Traits\{Queries, QuestionnaireKeys};
use App\Http\Resources\{
    ComponentResource,
    GroupEnvironmentResource,
    SimulatorPluginResource,
    TestStepResource
};
use App\Models\{
    Component,
    FileEnvironment,
    GroupEnvironment,
    QuestionnaireQuestions,
    Session,
    TestCase,
    TestStep
};
use App\Utils\AuditLogUtil;
use Arr;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\{
    RedirectResponse,
    Request,
    Resources\Json\AnonymousResourceCollection
};
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ConfigController extends Controller
{
    use Queries, QuestionnaireKeys, WithSutUrls;

    public function __construct()
    {
        $this->middleware(
            EnsureSessionIsPresent::class .
                ":session.type,session.sut,session.info.name{$this->getQuestionnaireKeys()}"
        )->only('index');
    }

    public function index(): Response
    {
        $sutIds = collect(session('session.sut'))
            ->filter(function ($sut) {
                return !$sut['implicit_sut_id'];
            })
            ->keys() ?: [0];

        return Inertia::render('sessions/register/config', [
            'session' => session('session'),
            'suts' => ComponentResource::collection(
                Component::with('connections')
                    ->whereIn('id', $sutIds)
                    ->get()
            ),
            'components' => ComponentResource::collection(
                $this->getComponents()
            ),
            'hasGroupEnvironments' => GroupEnvironment::whereHas(
                'group',
                function (Builder $query) {
                    $query->whereHas('users', function (Builder $query) {
                        $query->whereKey(
                            auth()
                                ->user()
                                ->getAuthIdentifier()
                        );
                    });
                }
            )->exists(),
            'testSteps' => TestStepResource::collection(
                TestStep::with(['source', 'target'])
                    ->whereIn(
                        'test_case_id',
                        session('session.info.test_cases', [0])
                    )
                    ->get()
            ),
            'sutUrls' => $this->getConfigUrls(),
            'simulatorPlugins' => SimulatorPluginResource::collection(
                Auth::user()
                    ->groups->pluck('simulatorPlugins')
                    ->flatten()
            ),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'group_environment_id' => [
                'nullable',
                'exists:group_environments,id',
            ],
            'environments' => ['nullable', 'array'],
            'fileEnvironments' => ['nullable', 'array'],
            'groupsDefault' => ['nullable', 'array'],
            'groupsDefault.*.id' => ['required', 'exists:groups,id'],
            'simulatorPlugin.id' => ['nullable', 'exists:simulator_plugins,id'],
        ]);

        try {
            $session = DB::transaction(function () use ($request) {
                /** @var Session $session */
                $session = auth()
                    ->user()
                    ->sessions()
                    ->create(
                        collect(session('session.info'))
                            ->merge($request->input())
                            ->merge([
                                'type' => session('session.type'),
                                'simulator_plugin_id' => $request->input(
                                    'simulatorPlugin.id'
                                ),
                            ])
                            ->all()
                    );

                FileEnvironment::syncEnvironments(
                    $session,
                    Arr::get($request->all(), 'fileEnvironments')
                );

                if ($session->isComplianceSession()) {
                    $session->updateStatus(Session::STATUS_READY);

                    $answers = Arr::collapse(session('session.questionnaire'));

                    QuestionnaireQuestions::whereIn(
                        'name',
                        array_keys($answers)
                    )
                        ->pluck('id', 'name')
                        ->each(function ($questionId, $questionName) use (
                            $answers,
                            $session
                        ) {
                            foreach (
                                (array) $answers[$questionName]
                                as $answer
                            ) {
                                $session->questionnaireAnswers()->create([
                                    'question_id' => $questionId,
                                    'answer' => $answer,
                                ]);
                            }
                        });
                }

                $session
                    ->testCases()
                    ->attach(
                        $session->isComplianceSession()
                            ? $this->getTestCasesQuery(
                                TestCase::query()
                            )->pluck('id')
                            : $request
                                ->session()
                                ->get('session.info.test_cases')
                    );

                collect(session('session.sut'))->each(function (
                    $component,
                    $id
                ) use ($session) {
                    $session
                        ->components()
                        ->attach(
                            $id,
                            Arr::only($component, [
                                'base_url',
                                'version',
                                'use_encryption',
                                'certificate_id',
                                'implicit_sut_id',
                            ])
                        );
                });

                if ($groupsDefault = $request->input('groupsDefault')) {
                    auth()
                        ->user()
                        ->groups()
                        ->whereKey(Arr::pluck($groupsDefault, 'id'))
                        ->wherePivot('admin', true)
                        ->each(function ($group) use ($session) {
                            $group->update([
                                'default_session_id' => $session->id,
                            ]);
                        });
                }

                return $session;
            });
            // log session creation
            new AuditLogUtil(
                $request,
                AuditActionEnum::SESSION_CREATED(),
                AuditTypeEnum::SESSION_TYPE,
                $session->id,
                $request->toArray()
            );
            $request->session()->remove('session');

            return redirect()
                ->route('sessions.show', $session)
                ->with('success', __('Session created successfully'));
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function groupEnvironmentCandidates(): AnonymousResourceCollection
    {
        return GroupEnvironmentResource::collection(
            GroupEnvironment::when(request('q'), function (Builder $query, $q) {
                $query->whereRaw('name like ?', "%{$q}%");
            })
                ->whereHas('group', function (Builder $query) {
                    $query->whereHas('users', function (Builder $query) {
                        $query->whereKey(
                            auth()
                                ->user()
                                ->getAuthIdentifier()
                        );
                    });
                })
                ->latest()
                ->paginate()
        );
    }
}
