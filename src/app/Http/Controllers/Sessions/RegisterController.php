<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureSessionIsPresent;
use App\Http\Resources\{
    ComponentResource,
    GroupEnvironmentResource,
    QuestionResource,
    SectionResource,
    UseCaseResource
};
use App\Models\{
    Component,
    GroupEnvironment,
    QuestionnaireQuestions,
    QuestionnaireSection,
    QuestionnaireTestCase,
    Session,
    TestCase,
    UseCase
};
use Arr;
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
     * @return Response
     */
    public function showTypeForm()
    {
        return Inertia::render('sessions/register/type', [
            'session' => session('session'),
            'testRunAttempts' => config(
                'test_cases.compliance_session_execution_limit',
                5
            ),
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
        return Inertia::render('sessions/register/sut', [
            'session' => session('session'),
            'suts' => ComponentResource::collection(
                Component::whereHas('testCases')->get()
            ),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
            ),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function storeSut(Request $request)
    {
        $request->validate([
            'base_url' => ['required', 'url', 'max:255'],
            'component_id' => ['required', 'exists:components,id'],
        ]);
        $request->session()->put('session.sut', $request->input());

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

    /**
     * @return Response
     */
    public function showInfoForm()
    {
        $testCases = $this->getTestCases();

        if (
            session('session.withQuestions') &&
            !session()->has('session.info')
        ) {
            session()->put(
                'session.info.test_cases',
                TestCase::whereIn('slug', $this->getTestCases(true) ?: [''])
                    ->available()
                    ->lastPerGroup()
                    ->pluck('id')
            );
        }

        return Inertia::render('sessions/register/info', [
            'session' => session('session'),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
            ),
            'useCases' => UseCaseResource::collection(
                UseCase::with([
                    'testCases' => function ($query) {
                        $this->getTestCasesQuery($query);
                    },
                ])
                    ->whereHas('testCases', function ($query) use ($testCases) {
                        $query
                            ->whereHas('components', function ($query) {
                                $query->whereKey(
                                    request()
                                        ->session()
                                        ->get('session.sut.component_id')
                                );
                            })
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

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function storeInfo(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'test_cases' => ['required', 'array', 'exists:test_cases,id'],
        ]);
        $request->session()->put(
            'session.info',
            array_merge($request->input(), [
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
        return Inertia::render('sessions/register/config', [
            'session' => session('session'),
            'sut' => (new ComponentResource(
                Component::whereKey(
                    request()
                        ->session()
                        ->get('session.sut.component_id')
                )
                    ->firstOrFail()
                    ->load('connections')
            ))->resolve(),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
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
                $sut = $request->session()->get('session.sut');

                /** @var Session $session */
                $session = auth()
                    ->user()
                    ->sessions()
                    ->create(
                        collect($request->session()->get('session.info'))
                            ->merge($request->input())
                            ->merge([
                                'type' => $request
                                    ->session()
                                    ->get('session.type'),
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
                $session->components()->attach([$sut]);

                return $session;
            });
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
            ->whereHas('components', function ($query) {
                $query->whereKey(
                    request()
                        ->session()
                        ->get('session.sut.component_id')
                );
            })
            ->where(function ($query) {
                $query->available();
            })
            ->when($testCases !== null, function (Builder $query) use (
                $testCases
            ) {
                $query->whereIn('slug', $testCases ?: ['']);
            })
            ->lastPerGroup();
    }
}
