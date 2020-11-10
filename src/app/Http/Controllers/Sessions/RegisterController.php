<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureSessionIsPresent;
use App\Http\Resources\{ComponentResource,
    GroupEnvironmentResource,
    QuestionResource,
    SectionResource,
    UseCaseResource};
use App\Models\{Component,
    GroupEnvironment,
    QuestionnaireQuestions,
    QuestionnaireSection,
    QuestionnaireTestCase,
    Session,
    TestCase,
    UseCase};
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
        $questionnaireKeys = implode(',', QuestionnaireSection::all()->map(function (QuestionnaireSection $section) {
            return "session.questionnaire.{$section->id}";
        })->toArray());

        $this->middleware(['auth', 'verified']);
        $this->middleware(EnsureSessionIsPresent::class . ":session.sut,{$questionnaireKeys}")->only(
            'info'
        );
        $this->middleware(
            EnsureSessionIsPresent::class . ":session.sut,session.info,{$questionnaireKeys}"
        )->only('config');
    }

    /**
     * @return Response
     */
    public function showSutForm()
    {
        return Inertia::render('sessions/register/sut', [
            'session'    => session('session'),
            'suts'       => ComponentResource::collection(
                Component::whereHas('testCases')->get()
            ),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
            ),
            'types'      => Session::typesList(),
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
            'base_url'     => ['required', 'url', 'max:255'],
            'component_id' => ['required', 'exists:components,id'],
            'type'         => ['required', Rule::in(array_keys(Session::types()))],
        ]);
        $request->session()->put('session.sut', $request->input());

        return $request->type == Session::TYPE_TEST
            ? redirect()->route('sessions.register.info')
            : redirect()->route('sessions.register.questionnaire', QuestionnaireSection::query()->first());
    }

    /**
     * @param QuestionnaireSection $section
     *
     * @return RedirectResponse|Response
     */
    public function showQuestionnaireForm(QuestionnaireSection $section)
    {
        if (QuestionnaireSection::where('id', $previousId = $section->id - 1)
                ->exists() && ! session()->exists("session.questionnaire.{$previousId}")) {
            return redirect()->route('sessions.register.questionnaire', $previousId);
        }

        return Inertia::render('sessions/register/questionnaire', [
            'session'   => session('session'),
            'sections'  => SectionResource::collection(QuestionnaireSection::all()),
            'questions' => QuestionResource::collection($section->questions),
        ]);
    }

    /**
     * @param Request $request
     * @param QuestionnaireSection $section
     *
     * @return RedirectResponse
     */
    public function storeQuestionnaire(Request $request, QuestionnaireSection $section)
    {
        $validators = [];
        $data = $request->all();

        foreach ($section->questions as $question) {
            if ($this->isRequiredAnswers($question, $data)) {
                $values = array_keys($question->values);

                $validators[$question->name] = [
                    'required',
                    $question->isMultiSelect() ? 'array' : Rule::in($values)
                ];

                if ($question->isMultiSelect()) {
                    $validators["{$question->name}.*"] = [Rule::in($values)];
                }
            }
        }

        $validated = $request->validate($validators);
        $request->session()->put("session.questionnaire.{$section->id}", $validated);

        return ($nextSection = QuestionnaireSection::where('id', $section->id + 1)->first())
            ? redirect()->route('sessions.register.questionnaire', $nextSection)
            : redirect()->route('sessions.register.questionnaire.summary');
    }

    /**
     * @return Response
     */
    public function questionnaireSummary()
    {
        return Inertia::render('sessions/register/summary', [
            'session'   => session('session'),
            'sections'  => SectionResource::collection(QuestionnaireSection::with('questions')->get()),
        ]);
    }

    /**
     * @return Response
     */
    public function showInfoForm()
    {
        $testCases = $this->getTestCases();

        return Inertia::render('sessions/register/info', [
            'session'    => session('session'),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
            ),
            'useCases'   => UseCaseResource::collection(
                UseCase::with([
                    'testCases' => function ($query) use ($testCases) {
                        $query
                            ->whereHas('components', function ($query) {
                                $query->whereKey(
                                    request()
                                        ->session()
                                        ->get('session.sut.component_id')
                                );
                            })
                            ->where(function ($query) {
                                $query
                                    ->where('public', true)
                                    ->orWhereHas('owner', function ($query) {
                                        $query->whereKey(
                                            auth()
                                                ->user()
                                                ->getAuthIdentifier()
                                        );
                                    })
                                    ->orWhereHas('groups', function ($query) {
                                        $query->whereHas('users', function (
                                            $query
                                        ) {
                                            $query->whereKey(
                                                auth()
                                                    ->user()
                                                    ->getAuthIdentifier()
                                            );
                                        });
                                    })
                                    ->when(
                                        auth()
                                            ->user()
                                            ->can(
                                                'viewAnyPrivate',
                                                TestCase::class
                                            ),
                                        function ($query) {
                                            $query->orWhere('public', false);
                                        }
                                    );
                            })
                            ->when(
                                $testCases !== null,
                                function (Builder $query) use ($testCases) {
                                    $query->whereIn('slug', $testCases ?: ['']);
                                }
                            );
                    },
                ])
                    ->whereHas('testCases', function ($query) {
                        $query
                            ->whereHas('components', function ($query) {
                                $query->whereKey(
                                    request()
                                        ->session()
                                        ->get('session.sut.component_id')
                                );
                            })
                            ->when(
                                ! auth()
                                    ->user()
                                    ->can('viewAny', TestCase::class),
                                function ($query) {
                                    $query->where('public', true);
                                }
                            );
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
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'test_cases'  => ['required', 'array', 'exists:test_cases,id'],
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
            'session'              => session('session'),
            'sut'                  => (new ComponentResource(
                Component::whereKey(
                    request()
                        ->session()
                        ->get('session.sut.component_id')
                )
                    ->firstOrFail()
                    ->load('connections')
            ))->resolve(),
            'components'           => ComponentResource::collection(
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
            'environments'         => ['nullable', 'array'],
        ]);

        try {
            $session = DB::transaction(function () use ($request) {
                $sut = $request->session()->get('session.sut');

                $session = auth()
                    ->user()
                    ->sessions()
                    ->create(
                        collect($request->session()->get('session.info'))
                            ->merge($request->input())
                            ->merge(Arr::only($sut, 'type'))
                            ->all()
                    );
                $session
                    ->testCases()
                    ->attach(
                        $request->session()->get('session.info.test_cases')
                    );
                $session
                    ->components()
                    ->attach([Arr::except($sut, 'type')]);

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
    protected function isRequiredAnswers(QuestionnaireQuestions $question, array $data): bool
    {
        foreach ($question->preconditions ?: [] as $attribute => $preconditions) {
            foreach ($preconditions as $rule => $precondition) {
                $precondition = (array) $precondition;
                if (isset($data[$attribute]) && is_array($data[$attribute])) {
                    foreach ($precondition as $item) {
                        if (in_array($item, $data[$attribute])) {
                            return true;
                        }
                    }

                    return false;
                }

                $validator = Validator::make($data, [
                    $attribute => ['required', "$rule:" . implode(',', $precondition)]
                ]);

                if ($validator->fails()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return array|null
     */
    protected function getTestCases()
    {
        if (session('session.sut.type') == Session::TYPE_COMPLIANCE) {
            $answers = [];
            foreach (session("session.questionnaire") as $sectionAnswers) {
                $answers = array_merge($answers, $sectionAnswers);
            }

            $testCases = [];
            QuestionnaireTestCase::query()->each(function (QuestionnaireTestCase $questionnaireTestCase) use ($answers, &$testCases) {
                $include = true;
                foreach ($questionnaireTestCase->matches as $attribute => $match) {
                    $includeMatch = false;
                    foreach ((array) $answers[$attribute] as $answer) {
                        $validator = Validator::make([$attribute => $answer], [$attribute => $match]);

                        if (!$validator->fails()) {
                            $includeMatch = true;
                        }
                    }

                    if (!$includeMatch) {
                        $include = false;
                    }
                }

                if ($include) {
                    $testCases[] = $questionnaireTestCase->test_case_slug;
                }
            });
        }

        return $testCases ?? null;
    }
}
