<?php declare(strict_types=1);

namespace App\Http\Controllers\Groups;

use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupUserInvitationResource;
use App\Models\Group;
use App\Models\GroupUserInvitation;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class GroupUserInvitationController extends Controller
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
        $this->authorize('admin', $group);

        return Inertia::render('groups/user-invitations/index', [
            'group' => (new GroupResource($group))->resolve(),
            'userInvitations' => GroupUserInvitationResource::collection(
                $group->userInvitations()
                    ->when(request('q'), function (Builder $query, $q) {
                        $query->where('email', 'like', "%{$q}%");
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

        return Inertia::render('groups/user-invitations/invite', [
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
        $request->validate(
            [
                'user_email' => [
                    'required',
                    'email',
                    Rule::unique('group_user_invitations', 'email')->where(function ($query) use ($group) {
                        return $query->where('group_id', $group->id);
                    }),
                    $group->emailRegexRule()
                ],
            ],
            ['user_email.unique' => 'Invitation for this email already exist.']
        );
        $request->validate(
            ['user_email' => 'unique:users,email'],
            ['user_email.unique' => 'User with this email already registered.']
        );

        $userInvitation = $group->userInvitations()->create([
            'email' => $request->user_email,
            'invitation_code' => GroupUserInvitation::generateInvitationCode(),
            'expired_at' => Env::get('EXPIRE_INVITATION', GroupUserInvitation::DEFAULT_EXPIRE_INVITATION)
        ]);
        $userInvitation->sendEmailInvitationNotification();

        return redirect()
            ->route('groups.user-invitations.index', $group)
            ->with('success', __('User invited successfully to group'));
    }

    /**
     * @param Group $group
     * @param GroupUserInvitation $userInvitation
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Group $group, GroupUserInvitation $userInvitation)
    {
        $userInvitation = $group
            ->userInvitations()
            ->whereKey($userInvitation->getKey())
            ->firstOrFail();
        $this->authorize('admin', $group);

        $userInvitation->update([
            'invitation_code' => GroupUserInvitation::generateInvitationCode(),
            'expired_at' => Env::get('EXPIRE_INVITATION', GroupUserInvitation::DEFAULT_EXPIRE_INVITATION)
        ]);
        $userInvitation->sendEmailInvitationNotification();

        return redirect()
            ->route('groups.user-invitations.index', $group)
            ->with('success', __('Invitation re-send successfully'));
    }

    /**
     * @param Group $group
     * @param GroupUserInvitation $userInvitation
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Group $group, GroupUserInvitation $userInvitation)
    {
        $userInvitation = $group
            ->userInvitations()
            ->whereKey($userInvitation->getKey())
            ->firstOrFail();
        $this->authorize('admin', $group);
        $userInvitation->delete();

        return redirect()
            ->back()
            ->with('success', __('Invitation deleted successfully'));
    }
}
