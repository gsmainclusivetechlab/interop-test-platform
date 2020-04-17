<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ScenarioResource;
use App\Http\Resources\TestCaseResource;
use App\Imports\TestCaseImport;
use App\Models\Scenario;
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
     * @param Scenario $scenario
     * @return \Inertia\Response
     */
    public function index(Scenario $scenario)
    {
        return Inertia::render('admin/test-cases/index', [
            'scenario' => (new ScenarioResource($scenario))->resolve(),
            'testCases' => TestCaseResource::collection(
                $scenario->testCases()
                    ->when(request('q'), function (Builder $query, $q) {
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
     * @param Scenario $scenario
     * @return \Inertia\Response
     */
    public function showImportForm(Scenario $scenario)
    {
        return Inertia::render('admin/test-cases/import', [
            'scenario' => (new ScenarioResource($scenario))->resolve(),
        ]);
    }

    /**
     * @param Scenario $scenario
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Scenario $scenario)
    {
        request()->validate([
            'file' => [
                'required',
                'mimetypes:text/yaml,text/plain',
            ],
        ]);
        $file = request()->file('file');

        try {
            $rows = Yaml::parse($file->get());
            (new TestCaseImport($scenario))->import($rows);
            return redirect()
                ->route('admin.scenarios.test-cases.index', $scenario)
                ->with('success', __('Test case imported successfully'));
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
