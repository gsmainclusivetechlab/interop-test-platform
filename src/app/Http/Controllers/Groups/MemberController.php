<?php declare(strict_types=1);

namespace App\Http\Controllers\Groups;

use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * MemberController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @param Group $group
     * @return \Inertia\Response
     */
    public function create(Group $group)
    {
        return Inertia::render('groups/members/create', [
            'group' => (new GroupResource($group))->resolve(),
        ]);
    }

    /**
     * @param Group $group
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Group $group, Request $request)
    {
        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('group_members', 'user_id')->where(function ($query) use ($group) {
                    return $query->where('group_id', $group->id);
                }),
            ],
            'admin' => ['required', 'bool'],
        ]);
        $group->members()->attach($request->input('user_id'), [
            'admin' => $request->input('admin'),
        ]);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', __('Member invited successfully to group'));
    }

    /**
     * @param Group $group
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function candidates(Group $group)
    {
        return UserResource::collection(
            User::when(request('q'), function (Builder $query, $q) use ($group) {
                $query->whereRaw(
                    'CONCAT(first_name, " ", last_name) like ?',
                    "%{$q}%"
                );
            })
                ->where('email', 'like', "%{$group->domain}")
                ->whereDoesntHave('groups', function (Builder $query) use ($group) {
                    $query->where('id', $group->id);
                })
                ->latest()
                ->paginate()
        );
    }
}
