<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin\TestCases;

use App\Http\Resources\GroupResource;
use App\Http\Resources\TestCaseResource;
use App\Models\Group;
use App\Models\TestCase;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TestCaseGroupController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('test-case.latest')->only(['index']);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function index(TestCase $testCase)
    {
        $this->authorize('update', $testCase);
        return Inertia::render('admin/test-cases/groups/index', [
            'testCase' => (new TestCaseResource($testCase))->resolve(),
            'groups' => GroupResource::collection(
                $testCase->groups()->paginate()
            ),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(TestCase $testCase, Request $request)
    {
        $this->authorize('update', $testCase);
        $request->validate([
            'groups_id.*' => [
                'required',
                'exists:groups,id',
                Rule::unique('group_test_cases', 'group_id')->where(function (
                    $query
                ) use ($testCase) {
                    return $query->where('test_case_id', $testCase->id);
                }),
            ],
        ]);
        $testCase->groups()->attach($request->input('groups_id'));

        return redirect()
            ->route('admin.test-cases.groups.index', $testCase->id)
            ->with('success', __('Groups added successfully to test case'));
    }

    /**
     * @param TestCase $testCase
     * @param Group $group
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(TestCase $testCase, Group $group)
    {
        $this->authorize('update', $testCase);
        $group = $testCase
            ->groups()
            ->whereKey($group->getKey())
            ->firstOrFail();
        $testCase->groups()->detach($group);

        return redirect()
            ->back()
            ->with('success', __('Group deleted successfully from test case'));
    }
}
