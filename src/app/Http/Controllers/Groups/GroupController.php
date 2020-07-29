<?php declare(strict_types=1);

namespace App\Http\Controllers\Groups;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Http\Controllers\Controller;
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
        $this->authorizeResource(Group::class, 'group', [
            'only' => ['index', 'show'],
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('groups/index', [
            'groups' => GroupResource::collection(
                Group::when(request('q'), function (Builder $query, $q) {
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
     */
    public function show(Group $group)
    {
        return Inertia::render('groups/show', [
            'group' => (new GroupResource($group))->resolve(),
        ]);
    }
}
