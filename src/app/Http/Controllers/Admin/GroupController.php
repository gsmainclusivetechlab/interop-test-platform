<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GroupController extends Controller
{
    /**
     * GroupController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(Group::class, 'group', [
            'except' => ['show'],
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('admin/groups/index', [
            'groups' => GroupResource::collection(
                Group::when(request('q'), function (Builder $query, $q) {
                    $query->where('name', 'like', "%{$q}%");
                })
                    ->with(['members', 'sessions'])
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('admin/groups/create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255', 'regex:/^@.+$/i'],
            'description' => ['string', 'nullable'],
        ]);
        Group::create($request->input());

        return redirect()
            ->route('admin.groups.index')
            ->with('success', __('Group created successfully'));
    }

    /**
     * @param Group $group
     * @return \Inertia\Response
     */
    public function edit(Group $group)
    {
        return Inertia::render('admin/groups/edit', [
            'group' => (new GroupResource($group))->resolve(),
        ]);
    }

    /**
     * @param Group $group
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Group $group, Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255', 'regex:/^@.+$/i'],
            'description' => ['string', 'nullable'],
        ]);
        $group->update($request->input());

        return redirect()
            ->route('admin.groups.index')
            ->with('success', __('Group updated successfully'));
    }

    /**
     * @param Group $group
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return redirect()
            ->back()
            ->with('success', __('Group deleted successfully'));
    }
}
