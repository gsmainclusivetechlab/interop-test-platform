<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exports\TestCaseExport;
use App\Http\Resources\ApiSpecResource;
use App\Http\Resources\ComponentResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\TestCaseResource;
use App\Http\Resources\TestStepResource;
use App\Http\Resources\UseCaseResource;
use App\Imports\TestCaseImport;
use App\Models\ApiSpec;
use App\Models\Component;
use App\Models\Group;
use App\Models\TestCase;
use App\Http\Controllers\Controller;
use App\Models\TestStep;
use App\Models\UseCase;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
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
        $this->authorizeResource(TestCase::class, 'test_case', [
            'except' => [
                'show',
                'edit',
                'update'
            ],
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
                    TestCase::BEHAVIOR_NEGATIVE
                ])
            ],
            'slug' => [
                'nullable',
                Rule::unique('test_cases'),
            ],
            'use_case_id' => ['required', 'integer', 'exists:use_cases,id'],
            'groups_id.*' => ['integer', 'exists:groups,id'],
            'components_id.*' => ['integer', 'exists:components,id'],
        ]);

        $testCase = TestCase::create(array_merge($request->input(), [
            'draft' => true,
        ]));
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
        $testCase->delete();

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
            }
            $testCase
                ->owner()
                ->associate(auth()->user())
                ->save();
            return redirect()
                ->route('admin.test-cases.index')
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
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function showInfo(TestCase $testCase)
    {
        if (!$testCase->isLast()) {
            return $this->redirectToLast($testCase->last_version->id);
        }
        $this->authorize('update', $testCase);
        return Inertia::render('admin/test-cases/info/show', [
            'testCase' => (new TestCaseResource(
                $testCase->load([
                    'useCase',
                    'components'
                ])
            ))->resolve(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function editInfo(TestCase $testCase)
    {
        if (!$testCase->isLast()) {
            return $this->redirectToLast($testCase->last_version->id);
        }
        $this->authorize('update', $testCase);
        $testCaseResource = (new TestCaseResource(
            $testCase->load([
                'groups',
                'useCase',
                'testSteps',
                'components'
            ])
        ))->resolve();

        if (!$testCase->draft) {
            try {
                $rows = array_merge(Yaml::parse((new TestCaseExport())->export($testCase)), [
                    'test_case_group_id' => $testCase->test_case_group_id,
                    'public' => $testCase->public,
                    'draft' => true,
                ]);
                $draftTestCase = (new TestCaseImport())->import($rows);
                $draftTestCase->groups()->sync($testCase->groups()->pluck('id'));
//                $draftTestCase = $testCase->replicate(['uuid']);
//                $draftTestCase->draft = 1;
//                $draftTestCase->push();
//                $draftTestCase->groups()->sync($testCase->groups()->pluck('id'));
//                $draftTestCase->components()->sync($testCase->components()->pluck('id'));
//                foreach ($testCase->testSteps()->get() as $testStep) {
//                    /** @var TestStep $testStep */
//                    $draftTestStep = $draftTestCase->testSteps()->make($testStep->getAttributes());
//                    $draftTestStep->saveOrFail();
//                }
//                $draftTestCase->components()->sync($testCase->components()->pluck('id'));

                $draftTestCase
                    ->owner()
                    ->associate(auth()->user())
                    ->save();

                return redirect()
                    ->route('admin.test-cases.info.edit', $draftTestCase->id)
                    ->with('success', __('New draft test case created successfully'));
            } catch (\Throwable $e) {
                $errorMessage = implode(
                    '<br>',
                    array_merge(
                        [$e->getMessage()],
                        !empty($e->validator)
                            ? $e->validator
                            ->errors()
                            ->all()
                            : []
                    )
                );
                return redirect()
                    ->back()
                    ->with('error', $errorMessage);
            }
        }

        return Inertia::render('admin/test-cases/info/edit', [
            'testCase' => $testCaseResource,
            'components' => ComponentResource::collection(
                Component::get()
            )->resolve(),
            'useCases' => UseCaseResource::collection(
                UseCase::get()
            )->resolve(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function updateInfo(TestCase $testCase, Request $request)
    {
        $this->authorize('update', $testCase);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                Rule::unique('test_cases')->ignore(
                    $testCase->test_case_group_id,
                    'test_case_group_id'
                ),
            ],
            'description' => ['string', 'nullable'],
            'precondition' => ['string', 'nullable'],
            'behavior' => ['required', 'string', 'max:255'],
            'use_case_id' => ['required', 'integer', 'exists:use_cases,id'],
            'groups_id.*' => ['integer', 'exists:groups,id'],
            'components_id.*' => ['integer', 'exists:components,id'],
        ]);
        $testCase->update($request->input());
        $testCase->components()->sync($request->input('components_id'));
        $testCase->groups()->sync($request->input('groups_id'));

        return redirect()
            ->route('admin.test-cases.info.show', $testCase->id)
            ->with('success', __('Test case updated successfully'));
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function indexGroups(TestCase $testCase)
    {
        if (!$testCase->isLast()) {
            return $this->redirectToLast($testCase->last_version->id);
        }
        $this->authorize('update', $testCase);
        return Inertia::render('admin/test-cases/groups/index', [
            'testCase' => (new TestCaseResource($testCase))->resolve(),
            'groups' => GroupResource::collection(
                $testCase
                    ->groups()
                    ->paginate()
            )
        ]);
    }

    /**
     * @param TestCase $testCase
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function storeGroups(TestCase $testCase, Request $request)
    {
        $this->authorize('update', $testCase);
        $request->validate([
            'groups_id.*' => [
                'required',
                'exists:groups,id',
                Rule::unique('group_test_cases', 'group_id')->where(function (
                    $query
                ) use ($testCase) {
                    return $query->where('test_case_id', $testCase->id);
                }),
            ],
        ]);
        $testCase->groups()->attach($request->input('groups_id'));

        return redirect()
            ->route('admin.test-cases.groups.index', $testCase->id)
            ->with('success', __('Groups added successfully to test case'));
    }

    /**
     * @param TestCase $testCase
     * @param Group $group
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroyGroups(TestCase $testCase, Group $group)
    {
        $this->authorize('update', $testCase);
        $group = $testCase
            ->groups()
            ->whereKey($group->getKey())
            ->firstOrFail();
        $testCase->groups()->detach($group);

        return redirect()
            ->back()
            ->with('success', __('Group deleted successfully from test case'));
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function indexTestSteps(TestCase $testCase)
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
    public function createTestSteps(TestCase $testCase)
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
            'methods' => TestStep::getMethodList(),
            'statuses' => TestStep::getStatusList(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function storeTestSteps(TestCase $testCase, Request $request)
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
        TestStep::create(
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
     */
    public function editTestSteps(TestCase $testCase, TestStep $testStep)
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
            'methods' => TestStep::getMethodList(),
            'statuses' => TestStep::getStatusList(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function updateTestSteps(
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
    public function destroyTestSteps(TestCase $testCase, TestStep $testStep)
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
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function indexVersions(TestCase $testCase)
    {
        if (!$testCase->isLast()) {
            return $this->redirectToLast($testCase->last_version->id);
        }
        $this->authorize('update', $testCase);
        return Inertia::render('admin/test-cases/versions/index', [
            'currentTestCase' => (new TestCaseResource($testCase))->resolve(),
            'testCases' => TestCaseResource::collection(
                TestCase::where(
                    'test_case_group_id',
                    $testCase->test_case_group_id
                )
                    ->with('useCase')
                    ->orderByDesc('version')
                    ->paginate()
            )
        ]);
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
        header("Content-Disposition: attachment; filename=\"{$fileName}\".yaml");
        header('Content-Length: ' . strlen($data));

        $file = fopen('php://output', 'w') or die('Unable to open file!');
        fwrite($file, $data);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function complete(TestCase $testCase)
    {
        $this->authorize('update', $testCase);
        $testCase->update(['draft' => 0]);

        return redirect()->back();
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
     * @param string|array $params
     * @return RedirectResponse
     */
    protected function redirectToLast($params)
    {
        return redirect()->route(\Route::currentRouteName(), $params);
    }
}
