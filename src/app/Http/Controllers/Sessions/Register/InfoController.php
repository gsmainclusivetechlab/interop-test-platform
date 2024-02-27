<?php

namespace App\Http\Controllers\Sessions\Register;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Sessions\Register\Traits\{Queries, QuestionnaireKeys};
use App\Http\Middleware\EnsureSessionIsPresent;
use App\Http\Resources\{UseCaseResource, ScenarioResource};
use Illuminate\Http\{RedirectResponse, Request};
use Inertia\Inertia;
use Inertia\Response;
use Str;

class InfoController extends Controller
{
    use Queries, QuestionnaireKeys;

    public function __construct()
    {
        $this->middleware(
            EnsureSessionIsPresent::class .
                ":session.type{$this->getQuestionnaireKeys()}"
        )->only('index');
    }

    public function index(): Response
    {
        $useCases = $this->getUseCases();
        $availableTestCasesIds = $useCases
            ->pluck('testCases')
            ->flatten()
            ->pluck('id');

        if ($withQuestions = session('session.withQuestions')) {
            $ids = $this->getTestCasesIds($availableTestCasesIds);

            if (!session()->has('session.info')) {
                session()->put('session.info.test_cases', $ids);
            }
        }

        return Inertia::render('sessions/register/info', [
            'session' => session('session'),
            'hasDifferentAnswers' =>
                $withQuestions &&
                (collect(
                    $testCasesIds = session()->get('session.info.test_cases')
                )
                    ->diff($ids)
                    ->count() > 0 ||
                    count($testCasesIds) != count($ids)),
            'useCases' => UseCaseResource::collection($useCases),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'description' => ['string', 'nullable'],
                'test_cases' => ['required', 'array', 'exists:test_cases,id'],
            ],
            [
                'test_cases.required' => __(
                    'Please select at least 1 test case.'
                ),
            ]
        );
        $request->session()->put(
            'session.info',
            array_merge($validated, [
                'uuid' => Str::uuid(),
            ])
        );

        return redirect()->route('sessions.register.sut');
    }

    public function resetTestCases(): RedirectResponse
    {
        $availableTestCasesIds = $this->getUseCases()
            ->pluck('testCases')
            ->flatten()
            ->pluck('id');

        session()->put(
            'session.info.test_cases',
            $this->getTestCasesIds($availableTestCasesIds)
        );

        return redirect()->route('sessions.register.info');
    }
}
