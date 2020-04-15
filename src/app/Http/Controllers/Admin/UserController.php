<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('admin/users/index', [
            'users' => UserResource::collection(
                User::when(request('q'), function (Builder $query, $q) {
                    $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('company', 'like', "%{$q}%");
                })
                    ->when(request()->route()->hasParameter('trashed'), function (Builder $query, $trashed) {
                        return $trashed ? $query->onlyTrashed() : $query->withoutTrashed();
                    })
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
                'trashed' => request()->route()->hasParameter('trashed'),
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
    public function promoteAdmin(User $user)
    {
        $this->authorize('promoteAdmin', $user);
        $user->update(['role' => User::ROLE_ADMIN]);

        return redirect()
            ->back()
            ->with('success', __('User promoted to admin successfully'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function relegateAdmin(User $user)
    {
        $this->authorize('relegateAdmin', $user);
        $user->update(['role' => User::ROLE_USER]);

        return redirect()
            ->back()
            ->with('success', __('User relegated from admin successfully'));
    }
}
