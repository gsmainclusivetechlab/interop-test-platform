<?php declare(strict_types=1);

namespace App\Http\Controllers\Groups;

use App\Enums\AuditActionEnum;
use App\Enums\AuditTypeEnum;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupUserResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\AuditLogUtil;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class GroupUserController extends Controller
{
    /**
     * @param Group $group
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Group $group)
    {
        $this->authorize('view', $group);

        return Inertia::render('groups/users/index', [
            'group' => (new GroupResource($group))->resolve(),
            'users' => GroupUserResource::collection(
                $group
                    ->users()
                    ->when(request('q'), function (Builder $query, $q) {
                        $query
                            ->whereRaw(
                                'CONCAT(first_name, " ", last_name) like ?',
                                "%{$q}%"
                            )
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('company', 'like', "%{$q}%");
                    })
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
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
        return Inertia::render('groups/users/invite', [
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
            'admin' => !$group->users()->exists(),
        ]);

        new AuditLogUtil(
            $request,
            AuditActionEnum::GROUP_INVITE(),
            AuditTypeEnum::GROUP_TYPE,
            $group->id,
            $request->toArray()
        );

        return redirect()
            ->route('groups.users.index', $group)
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
            ->whereKey($user->getKey())
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
            ->whereKey($user->getKey())
            ->firstOrFail();
        $this->authorize('toggleAdmin', $user->pivot);
        $group
            ->users()
            ->updateExistingPivot($user, ['admin' => !$user->pivot->admin]);

        return redirect()->back();
    }

    /**
     * @param Group $group
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function candidates(Group $group)
    {
        $this->authorize('admin', $group);

        return UserResource::collection(
            User::when(request('q'), function (Builder $query, $q) use (
                $group
            ) {
                $query->whereRaw(
                    'CONCAT(first_name, " ", last_name) like ?',
                    "%{$q}%"
                );
            })
                ->where(function (Builder $query) use ($group) {
                    $domains = (array) explode(', ', $group->domain);
                    foreach ($domains as $domain) {
                        $query->orWhere('email', 'like', "%{$domain}");
                    }
                })
                ->whereDoesntHave('groups', function (Builder $query) use (
                    $group
                ) {
                    $query->whereKey($group->getKey());
                })
                ->latest()
                ->paginate()
        );
    }
}
