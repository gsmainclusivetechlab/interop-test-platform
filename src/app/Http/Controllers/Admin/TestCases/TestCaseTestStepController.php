<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin\TestCases;

use App\Http\Resources\{
    ApiSpecResource,
    ComponentResource,
    TestCaseResource,
    TestStepResource,
};
use App\Models\{
    ApiSpec,
    Component,
    TestCase,
    TestStep,
};
use App\Enums\HttpMethod;
use App\Enums\HttpStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestStep;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TestCaseTestStepController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(TestStep::class, 'test_step', [
            'except' => [
                'show',
            ],
        ]);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function index(TestCase $testCase)
    {
        if (!$testCase->isLast()) {
            return $this->redirectToLast($testCase->last_version->id);
        }
        $this->authorize('update', $testCase);
        return Inertia::render('admin/test-cases/test-steps/index', [
            'testCase' => (new TestCaseResource(
                $testCase->load(['testSteps'])
            ))->resolve(),
            'testSteps' => TestStepResource::collection(
                $testCase
                    ->testSteps()
                    ->with(['source', 'target'])
                    ->get()
            ),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function create(TestCase $testCase)
    {
        if (!$testCase->isLast()) {
            return $this->redirectToLast($testCase->last_version->id);
        }
        $this->authorize('update', $testCase);
        return Inertia::render('admin/test-cases/test-steps/create', [
            'testCase' => (new TestCaseResource(
                $testCase->load(['testSteps'])
            ))->resolve(),
            'components' => ComponentResource::collection(
                Component::get()
            )->resolve(),
            'apiSpecs' => ApiSpecResource::collection(
                ApiSpec::get()
            )->resolve(),
            'methods' => HttpMethod::list(),
            'statuses' => HttpStatus::list(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @param StoreTestStep $storeTestStepRequest
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function store(TestCase $testCase, StoreTestStep $storeTestStepRequest)
    {
        $this->authorize('update', $testCase);

        try {
            $storeTestStepRequest->createTestStep($testCase);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.test-cases.test-steps.index', $testCase->id)
            ->with('success', __('Test Step created successfully'));
    }

    /**
     * @param TestCase $testCase
     * @param TestStep $testStep
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function edit(TestCase $testCase, TestStep $testStep)
    {
        if (!$testCase->isLast()) {
            return $this->redirectToLast($testCase->last_version->id);
        }
        $this->authorize('update', $testCase);
        return Inertia::render('admin/test-cases/test-steps/edit', [
            'testCase' => (new TestCaseResource(
                $testCase->load(['testSteps'])
            ))->resolve(),
            'testStep' => (new TestStepResource(
                $testStep->load([
                    'source',
                    'target',
                    'apiSpec',
                    'testSetups',
                    'testScripts',
                ])
            ))->resolve(),
            'components' => ComponentResource::collection(
                Component::get()
            )->resolve(),
            'apiSpecs' => ApiSpecResource::collection(
                ApiSpec::get()
            )->resolve(),
            'methods' => HttpMethod::list(),
            'statuses' => HttpStatus::list(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @param TestStep $testStep
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function update(
        TestCase $testCase,
        TestStep $testStep,
        Request $request
    )
    {
        $this->authorize('update', $testCase);
        $request->validate([
            'test_case_id' => [
                'required|exists:test_cases,id',
                Rule::in([$testCase->id])
            ],
            'source_id' => 'required|exists:components,id',
            'target_id' => 'required|exists:components,id',
            'api_spec_id' => 'nullable|exists:api_specs,id',
            'path' => 'required|string|max:255',
            'method' => 'required|string|max:255',
            'pattern' => 'required|string|max:255',
            'trigger' => 'string|nullable',
            'request' => 'string|nullable',
            'response' => 'string|nullable',
        ]);
        $testStep->update(
            Arr::only(
                $request->input(),
                TestStep::make()->getFillable()
            )
        );

        return redirect()->back();
    }

    /**
     * @param TestCase $testCase
     * @param TestStep $testStep
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(TestCase $testCase, TestStep $testStep)
    {
        $this->authorize('update', $testCase);
        $testStep = $testCase
            ->testSteps()
            ->whereKey($testStep->getKey())
            ->firstOrFail();
        $testStep->delete();

        return redirect()
            ->back()
            ->with('success', __('Test step deleted successfully from test case'));
    }

    /**
     * @param string|array $params
     * @return RedirectResponse
     */
    protected function redirectToLast($params)
    {
        return redirect()->route(\Route::currentRouteName(), $params);
    }
}
