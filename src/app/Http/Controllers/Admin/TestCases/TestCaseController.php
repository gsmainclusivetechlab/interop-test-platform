<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin\TestCases;

use App\Exports\TestCaseExport;
use App\Http\Resources\{
    ComponentResource,
    GroupResource,
    TestCaseResource,
    TestStepResource,
    UseCaseResource
};
use App\Imports\TestCaseImport;
use App\Models\{Component, Group, TestCase, TestStep, UseCase};
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\Yaml\Yaml;

class TestCaseController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('test-case.latest')->only([
            'showImportVersionForm',
            'flow',
        ]);
        $this->authorizeResource(TestCase::class, 'test_case', [
            'except' => ['show', 'edit', 'update'],
        ]);
    }

    /**
     * @return Response
     */
    public function index()
    {
        return Inertia::render('admin/test-cases/index', [
            'testCases' => TestCaseResource::collection(
                TestCase::when(request('q'), function (Builder $query, $q) {
                    $query->where('test_cases.name', 'like', "%{$q}%");
                })
                    ->lastPerGroup()
                    ->with(['owner', 'groups', 'useCase', 'testSteps'])
                    ->when(
                        !auth()
                            ->user()
                            ->can('viewAnyPrivate', TestCase::class),
                        function ($query) {
                            $query->orWhereHas('owner', function ($query) {
                                $query->whereKey(
                                    auth()
                                        ->user()
                                        ->getAuthIdentifier()
                                );
                            });
                        }
                    )
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }

    /**
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', TestCase::class);
        return Inertia::render('admin/test-cases/create', [
            'components' => ComponentResource::collection(
                Component::get()
            )->resolve(),
            'useCases' => UseCaseResource::collection(
                UseCase::get()
            )->resolve(),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', TestCase::class);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'precondition' => ['string', 'nullable'],
            'behavior' => [
                'required',
                'string',
                Rule::in([
                    TestCase::BEHAVIOR_POSITIVE,
                    TestCase::BEHAVIOR_NEGATIVE,
                ]),
            ],
            'slug' => ['required', Rule::unique('test_cases')],
            'use_case_id' => ['required', 'integer', 'exists:use_cases,id'],
            'groups_id.*' => ['integer', 'exists:groups,id'],
            'components_id.*' => ['integer', 'exists:components,id'],
        ]);

        $testCase = TestCase::create(
            array_merge($request->input(), [
                'draft' => true,
            ])
        );
        $testCase->components()->sync($request->input('components_id'));
        $testCase->groups()->sync($request->input('groups_id'));
        $testCase
            ->owner()
            ->associate(auth()->user())
            ->save();

        return redirect()
            ->route('admin.test-cases.info.show', $testCase->id)
            ->with('success', __('Test Case created successfully'));
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(TestCase $testCase)
    {
        $this->authorize('delete', $testCase);

        TestCase::where(
            'test_case_group_id',
            $testCase->test_case_group_id
        )->each(function ($testCase) {
            $testCase->delete();
        });

        return redirect()
            ->back()
            ->with('success', __('Test case deleted successfully'));
    }

    /**
     * @return Response
     * @throws AuthorizationException
     */
    public function showImportForm()
    {
        $this->authorize('create', TestCase::class);
        return Inertia::render('admin/test-cases/import');
    }

    /**
     * @param TestCase $testCase
     * @return Response
     * @throws AuthorizationException
     */
    public function showImportVersionForm(TestCase $testCase)
    {
        $this->authorize('update', $testCase);
        return Inertia::render('admin/test-cases/import-version', [
            'testCase' => (new TestCaseResource($testCase))->resolve(),
        ]);
    }

    /**
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function import()
    {
        $this->authorize('create', TestCase::class);
        request()->validate([
            'file' => ['required', 'mimetypes:text/yaml,text/plain'],
        ]);

        try {
            $rows = Yaml::parse(
                request()
                    ->file('file')
                    ->get()
            );

            $baseTestCaseId = request()->input('testCaseId');
            if (
                $baseTestCaseId &&
                ($baseTestCase = TestCase::findOrFail($baseTestCaseId))
            ) {
                $rows = array_merge($rows, [
                    'test_case_group_id' => $baseTestCase->test_case_group_id,
                    'public' => $baseTestCase->public,
                    'draft' => true,
                ]);
            }

            $testCase = (new TestCaseImport())->import($rows);
            if (
                !empty($baseTestCase) &&
                ($baseGroups = $baseTestCase->groups()->pluck('id'))
            ) {
                $testCase->groups()->sync($baseGroups);
                if ($baseTestCase->draft) {
                    $baseTestCase->delete();
                }
            }
            $testCase
                ->owner()
                ->associate(auth()->user())
                ->save();
            return redirect()
                ->route('admin.test-cases.versions.index', $testCase->id)
                ->with('success', __('Test case imported successfully'));
        } catch (\Throwable $e) {
            $errorMessage = implode(
                '<br>',
                array_merge(
                    [$e->getMessage()],
                    !empty($e->validator) ? $e->validator->errors()->all() : []
                )
            );
            return redirect()
                ->back()
                ->with('error', $errorMessage);
        }
    }

    /**
     * @param TestCase $testCase
     * @throws \Throwable
     */
    public function export(TestCase $testCase)
    {
        $data = (new TestCaseExport())->export($testCase);
        $fileName = "TestCase-{$testCase->name}";

        header('Content-Type: application/yaml');
        header(
            "Content-Disposition: attachment; filename=\"{$fileName}.yaml\""
        );
        header('Content-Length: ' . strlen($data));

        ($file = fopen('php://output', 'w')) or die('Unable to open file!');
        fwrite($file, $data);
    }

    /**
     * @param TestCase $testCase
     * @return Response
     */
    public function flow(TestCase $testCase)
    {
        return Inertia::render('admin/test-cases/flow', [
            'testCase' => (new TestCaseResource($testCase))->resolve(),
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
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function togglePublic(TestCase $testCase)
    {
        $this->authorize('togglePublic', $testCase);
        $testCase->update(['public' => !$testCase->public]);

        return redirect()->back();
    }

    /**
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function groupCandidates()
    {
        $this->authorize('viewAny', TestCase::class);

        return GroupResource::collection(
            Group::when(request('q'), function (Builder $query, $q) {
                $query->whereRaw('name like ?', "%{$q}%");
            })
                ->when(
                    !auth()
                        ->user()
                        ->can('viewAny', Group::class),
                    function (Builder $query) {
                        $query->whereHas('users', function ($query) {
                            $query->whereKey(
                                auth()
                                    ->user()
                                    ->getAuthIdentifier()
                            );
                        });
                    }
                )
                ->latest()
                ->paginate()
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    public function environmentCandidates(Request $request)
    {
        $testCases = TestCase::whereIn(
            'id',
            $request->input('testCasesIds', [])
        )->get();
        $environments = [];
        /** @var TestCase $testCase */
        foreach ($testCases as $testCase) {
            /** @var TestStep $testStep */
            foreach ($testCase->testSteps as $testStep) {
                $environments = array_merge(
                    $environments,
                    $testStep->getEnvironments()
                );
            }
        }

        $environments = array_unique($environments);
        $baseEnvironments = [
            'SP_BASE_URI',
            'MMO1_BASE_URI',
            'MOJALOOP_BASE_URI',
            'MMO2_BASE_URI',
        ];
        $environments = array_filter($environments, function ($value) use (
            $baseEnvironments
        ) {
            return !in_array($value, $baseEnvironments);
        });

        return array_values($environments);
    }
}
