<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\TestCaseResource;
use App\Imports\TestCaseImport;
use App\Models\TestCase;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
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
        $this->authorizeResource(TestCase::class, 'test_case');
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
                    ->with(['useCase', 'testSteps'])
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
     * @return \Inertia\Response
     */
    public function show(TestCase $testCase)
    {
        return Inertia::render('admin/test-cases/show', [
            'testCase' => (new TestCaseResource($testCase))->resolve(),
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
        return Inertia::render('admin/test-cases/import');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import()
    {
        request()->validate(['file' => ['required', 'mimetypes:text/yaml,text/plain']]);

        try {
            $rows = Yaml::parse(request()->file('file')->get());
            (new TestCaseImport())->import($rows);
            return redirect()
                ->route('admin.test-cases.index')
                ->with('success', __('Test case imported successfully'));
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
