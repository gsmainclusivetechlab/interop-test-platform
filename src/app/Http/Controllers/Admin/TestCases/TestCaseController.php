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
use Illuminate\Validation\ValidationException;
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

        Component::whereDoesntHave('testCases')->each(function (
            Component $component
        ) {
            $component->delete();
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
     * @return Response
     * @throws AuthorizationException
     */
    public function showBatchImportForm()
    {
        $this->authorize('create', TestCase::class);
        return Inertia::render('admin/test-cases/batch-import');
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
            if (!empty($baseTestCase)) {
                if ($baseGroups = $baseTestCase->groups()->pluck('id')) {
                    $testCase->groups()->sync($baseGroups);
                }
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
        } catch (ValidationException $e) {
            throw ValidationException::withMessages([
                'entries' => implode(
                    '<br>',
                    array_merge(
                        [
                            'Test Case validation failed. Please resolve errors listed below:',
                        ],
                        $e->validator->errors()->all()
                    )
                ),
            ]);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function batchImport()
    {
        $this->authorize('create', TestCase::class);
        request()->validate(
            [
                'file' => ['required', 'array'],
                'file.*' => ['required', 'mimetypes:text/yaml,text/plain']
            ],
            ['file.*.mimetypes' => 'The file must be a file of type: yaml, yml.']
        );

        $testCaseImport = new TestCaseImport();
        try {
            $files = request()->file('file');
            $testCaseImport->batchImport($files);

            return redirect()
                ->route('admin.test-cases.index')
                ->with('success', __('Test cases imported successfully'));
        } catch (ValidationException $e) {
            $withFileName = $testCaseImport->currentFileName
                ? "<hr><b>Errors in file '<u>{$testCaseImport->currentFileName}</u>':</b>"
                : '';

            throw ValidationException::withMessages([
                'entries' => implode(
                    '<br>',
                    array_merge(
                        [
                            'Test Case validation failed. Please resolve errors listed below:',
                            $withFileName
                        ],
                        $e->validator->errors()->all()
                    )
                ),
            ]);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param TestCase $testCase
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(TestCase $testCase)
    {
        return response()->streamDownload(function () use ($testCase) {
            echo (new TestCaseExport())->export($testCase);
        }, str_replace(
            '/',
            '',
            substr($testCase->slug ?: $testCase->name, 0, 50)
        ) . '.yaml');
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
        $env = [];
        $file_env = [];
        /** @var TestCase $testCase */
        foreach ($testCases as $testCase) {
            /** @var TestStep $testStep */
            foreach ($testCase->testSteps as $testStep) {
                $testStepEnvironments = $testStep->getEnvironments();

                $env = array_merge($env, $testStepEnvironments['env']);
                $file_env = array_merge(
                    $file_env,
                    $testStepEnvironments['file_env']
                );
            }
        }
        $environments = [
            'env' => array_values(array_unique($env)),
            'file_env' => array_values(array_unique($file_env)),
        ];

        return $environments;
    }
}
