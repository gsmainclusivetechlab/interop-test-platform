<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\GroupResource;
use App\Http\Resources\TestCaseResource;
use App\Imports\TestCaseImport;
use App\Models\Group;
use App\Models\TestCase;
use App\Http\Controllers\Controller;
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
            'only' => ['index', 'edit', 'update', 'destroy'],
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
        return Inertia::render('admin/test-cases/edit', [
            'testCase' => (new TestCaseResource(
                $testCase->load(['groups', 'useCase'])
            ))->resolve(),
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
            'groups_id.*' => ['integer', 'exists:groups,id'],
        ]);
        $testCase->update($request->input());
        $testCase->groups()->sync($request->input('groups_id'));

        return redirect()
            ->route('admin.test-cases.index')
            ->with('success', __('Test case updated successfully'));
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
