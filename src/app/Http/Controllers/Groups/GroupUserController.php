<?php declare(strict_types=1);

namespace App\Http\Controllers\Groups;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class GroupUserController extends Controller
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
    public function create(Group $group)
    {
        $this->authorize('invite', $group);
        return Inertia::render('groups/invite', [
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
        $this->authorize('invite', $group);
        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('group_users', 'user_id')->where(function (
                    $query
                ) use ($group) {
                    return $query->where('group_id', $group->id);
                }),
            ],
        ]);
        $group->users()->attach($request->input('user_id'), [
            'admin' => false,
        ]);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', __('User invited successfully to group'));
    }

    /**
     * @param Group $group
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Group $group, User $user)
    {
        $user = $group
            ->users()
            ->whereKey($user)
            ->firstOrFail();
        $this->authorize('delete', $user->pivot);
        $group->users()->detach($user);

        return redirect()
            ->back()
            ->with('success', __('User deleted successfully from group'));
    }

    /**
     * @param Group $group
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function toggleAdmin(Group $group, User $user)
    {
        $user = $group
            ->users()
            ->whereKey($user)
            ->firstOrFail();
        $this->authorize('toggleAdmin', $user->pivot);
        $group
            ->users()
            ->updateExistingPivot($user, ['admin' => !$user->pivot->admin]);

        return redirect()->back();
    }
}
