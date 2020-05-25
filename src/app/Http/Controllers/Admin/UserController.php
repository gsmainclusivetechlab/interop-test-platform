<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(User::class, 'user', [
            'only' => ['index', 'destroy'],
        ]);
    }

    /**
     * @param string|null $trash
     * @return \Inertia\Response
     */
    public function index($trash = null)
    {
        return Inertia::render('admin/users/index', [
            'users' => UserResource::collection(
                User::when(request('q'), function (Builder $query, $q) {
                    $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('company', 'like', "%{$q}%");
                })
                    ->when(($trash !== null), function (Builder $query) {
                        return $query->onlyTrashed();
                    })
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
                'trash' => ($trash !== null),
            ],
        ]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->back()
            ->with('success', __('User blocked successfully'));
    }

    /**
     * @param User $userOnlyTrashed
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore(User $userOnlyTrashed)
    {
        $this->authorize('restore', $userOnlyTrashed);
        $userOnlyTrashed->restore();

        return redirect()
            ->back()
            ->with('success', __('User unblocked successfully'));
    }

    /**
     * @param User $userWithTrashed
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function forceDestroy(User $userWithTrashed)
    {
        $this->authorize('delete', $userWithTrashed);
        $userWithTrashed->forceDelete();

        return redirect()
            ->back()
            ->with('success', __('User deleted successfully'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(User $user)
    {
        $this->authorize('verify', $user);
        $user->markEmailAsVerified();

        return redirect()
            ->back()
            ->with('success', __('User verified successfully'));
    }

    /**
     * @param User $user
     * @param string $role
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function promoteRole(User $user, string $role, Request $request)
    {
        $this->authorize('promoteRole', $user);
        Validator::make($request->route()->parameters(), [
            'role' => [
                'required',
                Rule::in(collect(User::getRoleNames())->except([User::ROLE_SUPERADMIN])->keys()),
            ],
        ])->validate();
        $user->update(['role' => $role]);

        return redirect()->back();
    }
}
