<?php declare(strict_types=1);

namespace App\Http\Controllers\Groups;

use App\Http\Resources\GroupMemberResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class GroupController extends Controller
{
    /**
     * GroupController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('groups/index', [
            'groups' => GroupResource::collection(
                auth()
                    ->user()
                    ->groups()
                    ->when(request('q'), function (Builder $query, $q) {
                    $query->where('name', 'like', "%{$q}%");
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
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Group $group)
    {
        $this->authorize('view', $group);

        return Inertia::render('groups/show', [
            'group' => (new GroupResource($group))->resolve(),
            'members' => GroupMemberResource::collection(
                $group->members()->when(request('q'), function (Builder $query, $q) {
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
}
