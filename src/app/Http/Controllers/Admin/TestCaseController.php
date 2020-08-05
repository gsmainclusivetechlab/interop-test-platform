<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\GroupResource;
use App\Http\Resources\TestCaseResource;
use App\Imports\TestCaseImport;
use App\Models\Group;
use App\Models\TestCase;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\Yaml\Yaml;

class TestCaseController extends Controller
{
    /**
     * TestCaseController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(TestCase::class, 'test_case', [
            'only' => ['index', 'edit', 'update', 'destroy'],
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('admin/test-cases/index', [
            'testCases' => TestCaseResource::collection(
                TestCase::when(request('q'), function (Builder $query, $q) {
                    $query->where('test_cases.name', 'like', "%{$q}%");
                })
                    ->with(['owner', 'groups', 'useCase', 'testSteps'])
                    ->when(
                        !auth()
                            ->user()
                            ->can('viewAnyPrivate', TestCase::class),
                        function ($query) {
                            $query->orWhereHas('owner', function ($query) {
                                $query->whereKey(auth()->user());
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
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(TestCase $testCase)
    {
        $testCase->delete();

        return redirect()
            ->back()
            ->with('success', __('Test case deleted successfully'));
    }

    /**
     * @return \Inertia\Response
     */
    public function showImportForm()
    {
        $this->authorize('create', TestCase::class);
        return Inertia::render('admin/test-cases/import');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
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
            $testCase = (new TestCaseImport())->import($rows);
            $testCase
                ->owner()
                ->associate(auth()->user())
                ->save();
            return redirect()
                ->route('admin.test-cases.index')
                ->with('success', __('Test case imported successfully'));
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param TestCase $testCase
     * @return \Inertia\Response
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
     * @return \Illuminate\Http\RedirectResponse
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
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function togglePublic(TestCase $testCase)
    {
        $this->authorize('togglePublic', $testCase);
        $testCase->update(['public' => !$testCase->public]);

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
                            $query->whereKey(auth()->user());
                        });
                    }
                )
                ->latest()
                ->paginate()
        );
    }
}
