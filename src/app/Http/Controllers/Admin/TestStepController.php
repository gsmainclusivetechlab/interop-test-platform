<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\TestCaseResource;
use App\Http\Resources\TestStepResource;
use App\Models\TestCase;
use App\Models\TestStep;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

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
     * @param TestCase $testCase
     * @return \Inertia\Response
     */
    public function index(TestCase $testCase)
    {
        return Inertia::render('admin/test-steps/index', [
            'testCase' => (new TestCaseResource($testCase))->resolve(),
            'testSteps' => TestStepResource::collection(
                $testCase->testSteps()
                    ->when(request('q'), function (Builder $query, $q) {
                        $query->whereRaw('CONCAT(forward, " ", backward) like ?', "%{$q}%");
                    })
                    ->with(['source', 'target', 'apiScheme'])
                    ->withCount(['testScripts'])
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }
}
