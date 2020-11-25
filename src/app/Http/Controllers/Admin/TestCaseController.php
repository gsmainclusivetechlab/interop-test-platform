<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exports\TestCaseExport;
use App\Http\Resources\ComponentResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\TestCaseResource;
use App\Http\Resources\UseCaseResource;
use App\Imports\TestCaseImport;
use App\Models\Component;
use App\Models\Group;
use App\Models\TestCase;
use App\Http\Controllers\Controller;
use App\Models\UseCase;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
            'except' => ['show'],
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
     */
    public function create()
    {
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
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'groups_id.*' => ['integer', 'exists:groups,id'],
            'components_id.*' => ['integer', 'exists:components,id'],
        ]);
        $testCase = TestCase::create(array_merge($request->input(), [
            'draft' => true,
        ]));
        $testCase->components()->sync($request->input('components_id'));
        $testCase->groups()->sync($request->input('groups_id'));

        return redirect()
            ->route('admin.test-cases.edit', $testCase->id)
            ->with('success', __('Test Case created successfully'));
    }

    /**
     * @param TestCase $testCase
     * @return Response
     */
    public function showInfo(TestCase $testCase)
    {
        return Inertia::render('admin/test-cases/show', [
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
     * @return Response
     */
    public function showGroups(TestCase $testCase)
    {
        return Inertia::render('admin/test-cases/show', [
            'testCase' => (new TestCaseResource(
                $testCase->load([
                    'groups',
                ])
            ))->resolve(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @return Response
     */
    public function showTestSteps(TestCase $testCase)
    {
        return Inertia::render('admin/test-cases/show', [
            'testCase' => (new TestCaseResource(
                $testCase->load([
                    'testSteps',
                ])
            ))->resolve(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @return Response
     */
    public function showVersions(TestCase $testCase)
    {
        return Inertia::render('admin/test-cases/show', [
            'testCase' => (new TestCaseResource(
                $testCase->load([
                    'testSteps',
                ])
            ))->resolve(),
            'testCases' => (new TestCaseResource(
                TestCase::where(
                    'test_case_group_id',
                    $testCase->test_case_group_id
                )->get()
            ))->resolve(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(TestCase $testCase)
    {
        $testCase->delete();

        return redirect()
            ->back()
            ->with('success', __('Test case deleted successfully'));
    }

    /**
     * @return Response
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
        $this->authorize('create', TestCase::class);
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
     * @return Response
     */
    public function edit(TestCase $testCase)
    {
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
                $testCaseResource = (new TestCaseResource(
                    $draftTestCase->load([
                        'groups',
                        'useCase',
                        'testSteps',
                        'components'
                    ])
                ))->resolve();
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

        return Inertia::render('admin/test-cases/edit', [
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
     */
    public function update(TestCase $testCase, Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'precondition' => ['required', 'string', 'nullable'],
            'behavior' => ['required', 'string', 'max:255'],
            'use_case_id' => ['required', 'integer', 'exists:use_cases,id'],
            'groups_id.*' => ['integer', 'exists:groups,id'],
            'components_id.*' => ['integer', 'exists:components,id'],
        ]);
        $testCase->update($request->input());
        $testCase->components()->sync($request->input('components_id'));
        $testCase->groups()->sync($request->input('groups_id'));

        return redirect()
            ->route('admin.test-cases.index')
            ->with('success', __('Test case updated successfully'));
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
        $this->authorize('create', $testCase);
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
}
