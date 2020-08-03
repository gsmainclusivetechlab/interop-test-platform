<?php declare(strict_types=1);

namespace App\Http\Controllers\Groups;

use App\Http\Resources\GroupUserResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Http\Controllers\Controller;
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
                    ->with(['users', 'sessions'])
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
            'sessions' => SessionResource::collection(
                $group
                    ->sessions()
                    ->with([
                        'owner',
                        'testCases' => function ($query) {
                            return $query->with(['lastTestRun']);
                        },
                        'lastTestRun',
                    ])
                    ->latest()
                    ->paginate()
            ),
            'users' => GroupUserResource::collection(
                $group
                    ->users()
                    ->latest()
                    ->get()
            ),
        ]);
    }

    /**
     * @param Group $group
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function userCandidates(Group $group)
    {
        $this->authorize('invite', $group);

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
                    $query->whereKey($group);
                })
                ->latest()
                ->paginate()
        );
    }
}
