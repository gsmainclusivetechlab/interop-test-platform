<?php declare(strict_types=1);

namespace App\Http\Controllers\Groups;

use App\Http\Resources\GroupResource;
use App\Http\Resources\SessionResource;
use App\Models\Group;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;

class GroupController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return Response
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
     * @return Response
     * @throws AuthorizationException
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
        ]);
    }
}
