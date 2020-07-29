<?php declare(strict_types=1);

namespace App\Http\Controllers\Groups;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * GroupController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
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
