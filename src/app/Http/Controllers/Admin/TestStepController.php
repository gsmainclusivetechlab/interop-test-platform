<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Imports\TestStepsImport;
use App\Models\TestCase;
use App\Models\TestStep;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class TestStepController extends Controller
{
    /**
     * TestStepController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(TestStep::class, 'test_step');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(TestCase $testCase)
    {
        $testSteps = $testCase->testSteps()
            ->when(request('q'), function (Builder $query, $q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate();

        return view('admin.test-steps.index', compact('testCase', 'testSteps'));
    }

    /**
     * @param TestStep $testStep
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TestStep $testStep)
    {
        return view('admin.test-steps.show', compact('testStep'));
    }

    /**
     * @param TestCase $testCase
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showImportForm(TestCase $testCase)
    {
        return view('admin.test-steps.import', compact('testCase'));
    }

    /**
     * @param TestCase $testCase
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(TestCase $testCase)
    {
        request()->validate(['file' => 'required|mimes:xml']);
        $import = new TestStepsImport($testCase);

        try {
            $import->import(request('file'));
            return redirect()
                ->route('admin.test-cases.test-steps.index', $testCase)
                ->with('success', __('Test steps imported successfully'));
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
