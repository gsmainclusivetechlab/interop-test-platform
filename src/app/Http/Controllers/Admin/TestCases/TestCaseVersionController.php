<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin\TestCases;

use App\Http\Resources\TestCaseResource;
use App\Models\TestCase;
use App\Http\Controllers\Controller;
use Exception;
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
            'only' => ['index'],
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
            return redirect()->route(
                \Route::currentRouteName(),
                $testCase->last_version->id
            );
        }
        $this->authorize('update', $testCase);
        return Inertia::render('admin/test-cases/versions/index', [
            'currentTestCase' => (new TestCaseResource($testCase))->resolve(),
            'testCases' => TestCaseResource::collection(
                TestCase::where(
                    'test_case_group_id',
                    $testCase->test_case_group_id
                )
                    ->with('useCase', 'owner')
                    ->orderByDesc('version')
                    ->paginate()
            ),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function publish(TestCase $testCase)
    {
        $this->authorize('update', $testCase);
        $testCase->update(['draft' => 0]);

        return redirect()->back();
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse
     * @throws Exception
     */
    public function discard(TestCase $testCase)
    {
        $this->authorize('delete', $testCase);
        $testCase->delete();

        if (1 === $testCase->version) {
            return redirect()
                ->route('admin.test-cases.index')
                ->with('success', __('Test case deleted successfully'));
        }
        return redirect()
            ->route(
                'admin.test-cases.versions.index',
                $testCase->last_version->id
            )
            ->with('success', __('Test case deleted successfully'));
    }
}
