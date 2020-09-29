<?php declare(strict_types=1);

namespace App\Http\Controllers\Groups;

use App\Http\Resources\GroupEnvironmentResource;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Models\GroupEnvironment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GroupEnvironmentController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @param Group $group
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Group $group)
    {
        $this->authorize('view', $group);

        return Inertia::render('groups/environments/index', [
            'group' => (new GroupResource($group))->resolve(),
            'environments' => GroupEnvironmentResource::collection(
                $group
                    ->environments()
                    ->latest()
                    ->paginate()
            ),
        ]);
    }

    /**
     * @param Group $group
     * @return Response
     * @throws AuthorizationException
     */
    public function create(Group $group)
    {
        $this->authorize('admin', $group);
        return Inertia::render('groups/environments/create', [
            'group' => (new GroupResource($group))->resolve(),
        ]);
    }

    /**
     * @param Group $group
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Group $group, Request $request)
    {
        $this->authorize('admin', $group);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'variables' => ['required'],
            'variables.*.name' => ['required'],
            'variables.*.value' => ['required'],
        ]);
        $group->environments()->create($request->input());

        return redirect()
            ->route('groups.environments.index', $group)
            ->with('success', __('Environment created successfully'));
    }

    /**
     * @param Group $group
     * @param GroupEnvironment $environment
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Group $group, GroupEnvironment $environment)
    {
        $environment = $group
            ->environments()
            ->whereKey($environment)
            ->firstOrFail();
        $this->authorize('update', $environment);
        return Inertia::render('groups/environments/edit', [
            'group' => (new GroupResource($group))->resolve(),
            'environment' => (new GroupEnvironmentResource($environment))->resolve(),
        ]);
    }

    /**
     * @param Group $group
     * @param GroupEnvironment $environment
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Group $group, GroupEnvironment $environment, Request $request)
    {
        $environment = $group
            ->environments()
            ->whereKey($environment)
            ->firstOrFail();
        $this->authorize('update', $group);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'variables' => ['required'],
            'variables.*.name' => ['required'],
            'variables.*.value' => ['required'],
        ]);
        $environment->update($request->input());

        return redirect()
            ->route('groups.environments.index', $group)
            ->with('success', __('Environment updated successfully'));
    }

    /**
     * @param Group $group
     * @param GroupEnvironment $environment
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(Group $group, GroupEnvironment $environment)
    {
        $environment = $group
            ->environments()
            ->whereKey($environment)
            ->firstOrFail();
        $this->authorize('delete', $environment);
        $environment->delete();

        return redirect()
            ->back()
            ->with('success', __('Environment deleted successfully'));
    }
}
