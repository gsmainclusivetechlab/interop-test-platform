<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Imports\TestCaseImport;
use App\Models\Scenario;
use App\Models\TestCase;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Scenario $scenario)
    {
        $testCases = $scenario->testCases()
            ->when(request('q'), function (Builder $query, $q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->with('useCase')
            ->withCount('testSteps')
            ->latest()
            ->paginate();

        return view('admin.test-cases.index', compact('scenario', 'testCases'));
    }

    /**
     * @param TestCase $testCase
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TestCase $testCase)
    {
        return view('admin.test-cases.show', compact('testCase'));
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showImportForm(Scenario $scenario)
    {
        return view('admin.test-cases.import', compact('scenario'));
    }

    /**
     * @param Scenario $scenario
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Scenario $scenario)
    {
        request()->validate(['file' => 'required']);
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
