<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin\TestCases;

use App\Http\Resources\TestCaseResource;
use App\Models\TestCase;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TestCaseVersionController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(TestCase::class, 'test_case', [
            'only' => [
                'index',
            ],
        ]);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function index(TestCase $testCase)
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
     * @param string|array $params
     * @return RedirectResponse
     */
    protected function redirectToLast($params)
    {
        return redirect()->route(\Route::currentRouteName(), $params);
    }
}
