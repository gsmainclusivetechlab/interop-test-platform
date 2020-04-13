<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

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
                $query->whereRaw('CONCAT(forward, " ", backward) like ?', "%{$q}%");
            })
            ->with(['source', 'target', 'apiScheme'])
            ->withCount(['testScripts'])
            ->paginate();

        return view('admin.test-steps.index', compact('testCase', 'testSteps'));
    }
}
