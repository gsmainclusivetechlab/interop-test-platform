<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Exceptions\MessageMismatchException;
use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureSessionIsPresent;
use App\Http\Requests\SessionSutRequest;
use App\Http\Resources\{
    CertificateResource,
    ComponentResource,
    GroupEnvironmentResource,
    QuestionResource,
    SectionResource,
    UseCaseResource
};
use App\Models\{
    Certificate,
    Component,
    GroupEnvironment,
    QuestionnaireQuestions,
    QuestionnaireSection,
    QuestionnaireTestCase,
    Session,
    TestCase,
    TestStep,
    UseCase
};
use Arr;
use App\Utils\AuditLogUtil;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;
use Validator;

class RegisterController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $questionnaireKeys = '';
        if (
            Session::isCompliance(session('session.type')) ||
            session('session.withQuestions')
        ) {
            $keys = QuestionnaireSection::query()->pluck('id');
            $questionnaireKeys =
                ',' .
                implode(
                    ',',
                    $keys
                        ->map(function ($section) {
                            return "session.questionnaire.{$section}";
                        })
                        ->all()
                );
        }

        $this->middleware(['auth', 'verified']);
        $this->middleware(
            EnsureSessionIsPresent::class . ':session.type'
        )->only('showQuestionnaireForm');
        $this->middleware(
            EnsureSessionIsPresent::class . ":session.type{$questionnaireKeys}"
        )->only('showSutForm');
        $this->middleware(
            EnsureSessionIsPresent::class .
                ":session.type,session.sut{$questionnaireKeys}"
        )->only('showInfoForm');
        $this->middleware(
            EnsureSessionIsPresent::class .
                ":session.type,session.sut,session.info.name{$questionnaireKeys}"
        )->only('showConfigForm');
    }

    /**
     * @return RedirectResponse|Response
     */
    public function showTypeForm()
    {
        $filteredAvailableModes = collect(
            $availableModes = config('service_session.available_modes')
        )
            ->filter()
            ->all();

        if (count($filteredAvailableModes) == 1) {
            switch ($key = array_key_first($filteredAvailableModes)) {
                case 'test':
                case 'test_questionnaire':
                    $type = Session::TYPE_TEST;
                    break;
                case 'compliance':
                    $type = Session::TYPE_COMPLIANCE;
                    break;
                default:
                    throw new MessageMismatchException(
                        null,
                        400,
                        'The available mode does not match any session type'
                    );
            }

            return $this->resolveSessionType(
                $type,
                $key == 'test_questionnaire'
            );
        }

        return Inertia::render('sessions/register/type', [
            'session' => session('session'),
            'testRunAttempts' => config(
                'service_session.compliance_session_execution_limit',
                5
            ),
            'availableModes' => $availableModes,
        ]);
    }

    /**
     * @param Request $request
     * @param string $type
     *
     * @return RedirectResponse
     */
    public function storeType(Request $request, $type)
    {
        $withQuestions = (bool) $request->get('withQuestions');

        Validator::validate(
            [
                'type' => $type,
            ],
            [
                'type' => [
                    'required',
                    Rule::in(array_keys(Session::getTypeNames())),
                ],
            ]
        );

        return $this->resolveSessionType($type, $withQuestions);
    }

    protected function resolveSessionType(
        string $type,
        bool $withQuestions
    ): RedirectResponse {
        session()->put([
            'session.type' => $type,
            'session.withQuestions' => $withQuestions,
        ]);

        return Session::isCompliance($type) || $withQuestions
            ? redirect()->route(
                'sessions.register.questionnaire',
                QuestionnaireSection::query()->first()
            )
            : redirect()->route('sessions.register.sut');
    }

    /**
     * @return Response
     */
    public function showSutForm()
    {
        $isCompliance = Session::isCompliance(session('session.type'));

        return Inertia::render('sessions/register/sut', [
            'session' => session('session'),
            'suts' => ComponentResource::collection(
                Component::when($isCompliance, function ($query) {
                    $query->whereHas('testCases', function ($query) {
                        $testCases = $this->getTestCases();

                        $query->whereIn('slug', $testCases ?: ['']);
                    });
                })
                    ->where('sutable', true)
                    ->get()
            ),
            'components' => $this->getComponents(),
            'hasGroupCertificates' =>
                Certificate::hasGroupCertificates() || $this->getSessionIds(),
        ]);
    }

    public function storeSut(SessionSutRequest $request): RedirectResponse
    {
        $data = collect($request->get('components'))
            ->map(function ($sut, $key) use ($request) {
                if (
                    (bool) $sut['use_encryption'] &&
                    !Arr::get($sut, 'certificate_id')
                ) {
                    $sut['certificate_id'] = Certificate::create([
                        'passphrase' => $sut['passphrase'],
                        'name' => Component::find($sut['id'])->name,
                        'ca_crt_path' => Certificate::storeFile(
                            $request,
                            "components.{$key}.ca_crt"
                        ),
                        'client_crt_path' => Certificate::storeFile(
                            $request,
                            "components.{$key}.client_crt"
                        ),
                        'client_key_path' => Certificate::storeFile(
                            $request,
                            "components.{$key}.client_key"
                        ),
                    ])->id;
                }

                return Arr::except($sut, [
                    'ca_crt',
                    'client_crt',
                    'client_key',
                    'passphrase',
                ]);
            })
            ->all();

        $request->session()->put('session.sut', $data);

        return redirect()->route('sessions.register.info');
    }

    /**
     * @param QuestionnaireSection $section
     *
     * @return RedirectResponse|Response
     */
    public function showQuestionnaireForm(QuestionnaireSection $section)
    {
        if (
            ($previous = QuestionnaireSection::previousSection($section->id)) &&
            !session()->exists("session.questionnaire.{$previous->id}")
        ) {
            return redirect()->route(
                'sessions.register.questionnaire',
                $previous
            );
        }

        return Inertia::render('sessions/register/questionnaire', [
            'previousSection' => $previous->id ?? null,
            'page' => QuestionnaireSection::where(
                'id',
                '<=',
                $section->id
            )->count(),
            'session' => session('session'),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
            ),
            'sections' => SectionResource::collection(
                QuestionnaireSection::all()
            ),
            'questions' => QuestionResource::collection($section->questions),
        ]);
    }

    /**
     * @param Request $request
     * @param QuestionnaireSection $section
     *
     * @return RedirectResponse
     */
    public function storeQuestionnaire(
        Request $request,
        QuestionnaireSection $section
    ) {
        $rules = $this->questionnaireRules($section, $request->all());
        $validated = $request->validate($rules, [
            'required' => __('This question is required.'),
        ]);
        $request
            ->session()
            ->put("session.questionnaire.{$section->id}", $validated);

        return ($nextSection = QuestionnaireSection::nextSection($section->id))
            ? redirect()->route('sessions.register.questionnaire', $nextSection)
            : redirect()->route('sessions.register.questionnaire.summary');
    }

    /**
     * @return Response
     */
    public function questionnaireSummary()
    {
        return Inertia::render('sessions/register/summary', [
            'session' => session('session'),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
            ),
            'sections' => SectionResource::collection(
                QuestionnaireSection::with('questions')->get()
            ),
        ]);
    }

    public function showInfoForm(): Response
    {
        $withQuestions = session('session.withQuestions');
        if ($withQuestions) {
            $ids = $this->getTestCasesIds();

            if (!session()->has('session.info')) {
                session()->put('session.info.test_cases', $ids);
            }
        }

        return Inertia::render('sessions/register/info', [
            'session' => session('session'),
            'components' => $this->getComponents(),
            'hasDifferentAnswers' =>
                $withQuestions &&
                (collect(
                    $testCasesIds = session()->get('session.info.test_cases')
                )
                    ->diff($ids)
                    ->count() > 0 ||
                    count($testCasesIds) != count($ids)),
            'useCases' => UseCaseResource::collection(
                UseCase::with([
                    'testCases' => function ($query) {
                        $this->getTestCasesQuery($query);
                    },
                ])
                    ->whereHas('testCases', function ($query) {
                        $testCases = $this->getTestCases();

                        $query
                            ->withComponents(array_keys(session('session.sut')))
                            ->when(
                                !auth()
                                    ->user()
                                    ->can('viewAny', TestCase::class),
                                function ($query) {
                                    $query->where('public', true);
                                }
                            )
                            ->when($testCases !== null, function (
                                Builder $query
                            ) use ($testCases) {
                                $query->whereIn('slug', $testCases ?: ['']);
                            });
                    })
                    ->get()
            ),
        ]);
    }

    public function resetTestCases(): RedirectResponse
    {
        session()->put('session.info.test_cases', $this->getTestCasesIds());

        return redirect()->route('sessions.register.info');
    }

    /**
     * @return mixed
     */
    protected function getTestCasesIds()
    {
        return TestCase::whereIn('slug', $this->getTestCases(true) ?: [''])
            ->available()
            ->lastPerGroup(false)
            ->pluck('id');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function storeInfo(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'test_cases' => ['required', 'array', 'exists:test_cases,id'],
        ]);
        $request->session()->put(
            'session.info',
            array_merge($validated, [
                'uuid' => Str::uuid(),
            ])
        );

        return redirect()->route('sessions.register.config');
    }

    /**
     * @return Response
     */
    public function showConfigForm()
    {
        $session = session('session');
        return Inertia::render('sessions/register/config', [
            'session' => $session,
            'suts' => ComponentResource::collection(
                Component::whereIn(
                    'id',
                    array_keys(session('session.sut', [0]))
                )
                    ->with('connections')
                    ->get()
            ),
            'components' => $this->getComponents(),
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
                TestStep::whereIn(
                    'test_case_id',
                    session('session.info.test_cases', [0])
                )
                    ->with(['source', 'target'])
                    ->get()
            ),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function storeConfig(Request $request)
    {
        $request->validate([
            'group_environment_id' => [
                'nullable',
                'exists:group_environments,id',
            ],
            'environments' => ['nullable', 'array'],
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
                            ])
                            ->all()
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
                                'use_encryption',
                                'certificate_id',
                            ])
                        );
                });

                return $session;
            });
            // log session creation
            new AuditLogUtil($request, 'Created a new session', strval($session->id), 1, $request->toArray());
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

    /**
     * @return AnonymousResourceCollection
     */
    public function groupEnvironmentCandidates()
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

    public function groupCertificateCandidates(): AnonymousResourceCollection
    {
        return CertificateResource::collection(
            Certificate::when(request('q'), function (Builder $query, $q) {
                $query->whereRaw('name like ?', "%{$q}%");
            })
                ->where(function (Builder $query) {
                    $query
                        ->whereHas('group', function (Builder $query) {
                            $query->whereHas('users', function (
                                Builder $query
                            ) {
                                $query->whereKey(
                                    auth()
                                        ->user()
                                        ->getAuthIdentifier()
                                );
                            });
                        })
                        ->when($this->getSessionIds(), function (
                            Builder $query,
                            $ids
                        ) {
                            $query->orWhereIn('id', $ids);
                        })
                        ->when(request('session'), function (
                            Builder $query,
                            $session
                        ) {
                            $query->orWhereHas('sessions', function (
                                Builder $query
                            ) use ($session) {
                                $query->whereKey($session);
                            });
                        });
                })
                ->latest()
                ->paginate()
        );
    }

    protected function getSessionIds(): array
    {
        return collect(session('session.sut'))
            ->pluck('certificate_id')
            ->filter()
            ->all();
    }

    /**
     * @param QuestionnaireQuestions $question
     * @param array $data
     *
     * @return bool
     */
    protected function isRequiredAnswers(
        QuestionnaireQuestions $question,
        array $data
    ): bool {
        foreach (
            $question->preconditions ?: []
            as $attribute => $preconditions
        ) {
            foreach ($preconditions as $rule => $precondition) {
                $precondition = (array) $precondition;
                if (isset($data[$attribute]) && is_array($data[$attribute])) {
                    $interection = array_uintersect(
                        $data[$attribute],
                        $precondition,
                        'strcasecmp'
                    );

                    return count($interection) > 0;
                }

                $validator = Validator::make($data, [
                    $attribute => [
                        'required',
                        "$rule:" . implode(',', $precondition),
                    ],
                ]);

                if ($validator->fails()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param bool $withQuestions
     *
     * @return array|null
     */
    protected function getTestCases($withQuestions = false)
    {
        if (Session::isCompliance(session('session.type')) || $withQuestions) {
            $answers = Arr::collapse(session('session.questionnaire'));

            $testCases = [];
            QuestionnaireTestCase::query()->each(function (
                QuestionnaireTestCase $questionnaireTestCase
            ) use ($answers, &$testCases) {
                if ($this->includeTestCase($questionnaireTestCase, $answers)) {
                    $testCases[] = $questionnaireTestCase->test_case_slug;
                }
            });
        }

        return $testCases ?? null;
    }

    /**
     * @param QuestionnaireTestCase $questionnaireTestCase
     * @param array $answers
     *
     * @return bool
     */
    protected function includeTestCase(
        QuestionnaireTestCase $questionnaireTestCase,
        $answers
    ): bool {
        foreach ($questionnaireTestCase->matches as $attribute => $match) {
            $hasAnswer = false;
            foreach ((array) Arr::get($answers, $attribute, []) as $answer) {
                $validator = Validator::make(
                    [$attribute => $answer],
                    [$attribute => $match]
                );

                if (!$validator->fails()) {
                    $hasAnswer = true;
                }
            }

            if (!$hasAnswer) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param QuestionnaireSection $section
     * @param array $data
     *
     * @return array
     */
    protected function questionnaireRules(
        QuestionnaireSection $section,
        $data
    ): array {
        $rules = [];
        foreach ($section->questions as $question) {
            if ($this->isRequiredAnswers($question, $data)) {
                $values = array_keys($question->values);

                $rules[$question->name] = [
                    'required',
                    $question->isMultiSelect() ? 'array' : Rule::in($values),
                ];

                if ($question->isMultiSelect()) {
                    $rules["{$question->name}.*"] = [Rule::in($values)];
                }
            }
        }

        return $rules;
    }

    /**
     * @param Builder $query
     *
     * @return \Illuminate\Database\Concerns\BuildsQueries|Builder|mixed
     */
    public function getTestCasesQuery($query)
    {
        $testCases = $this->getTestCases();

        return $query
            ->withComponents(array_keys(session('session.sut')))
            ->where(function ($query) {
                $query->available();
            })
            ->when($testCases !== null, function (Builder $query) use (
                $testCases
            ) {
                $query->whereIn('slug', $testCases ?: ['']);
            })
            ->lastPerGroup(false);
    }

    protected function getComponents()
    {
        $testCases = $this->getTestCases();
        $isCompliance = Session::isCompliance(session('session.type'));

        $complianceComponentsQuery = function ($query) use ($testCases) {
            $testCasesQuery = function ($query) use ($testCases) {
                $query->whereHas('testCase', function ($query) use (
                    $testCases
                ) {
                    $query->whereIn('slug', $testCases ?: ['']);
                });
            };

            $query
                ->whereHas('sourceTestSteps', $testCasesQuery)
                ->orWhereHas('targetTestSteps', $testCasesQuery);
        };

        $testComponentsQuery = function ($query) {
            $query->whereHas('sourceTestSteps')->orWhereHas('targetTestSteps');
        };

        return ComponentResource::collection(
            Component::when($isCompliance, $complianceComponentsQuery)
                ->when(!$isCompliance, $testComponentsQuery)
                ->with([
                    'connections' => function ($query) use (
                        $isCompliance,
                        $complianceComponentsQuery,
                        $testComponentsQuery
                    ) {
                        $query
                            ->when($isCompliance, $complianceComponentsQuery)
                            ->when(!$isCompliance, $testComponentsQuery);
                    },
                ])
                ->get()
        );
    }
}
