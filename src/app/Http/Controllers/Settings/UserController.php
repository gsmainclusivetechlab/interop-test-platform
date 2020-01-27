<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::latest()->when(request('q'), function ($query, $q) {
            return $query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('company', 'like', "%{$q}%");
        })->paginate();

        return view('settings.users.index', compact('users'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function trash()
    {
        $this->authorize('viewAny', User::class);
        $users = User::latest()->onlyTrashed()->when(request('q'), function ($query, $q) {
                return $query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('company', 'like', "%{$q}%");
        })->paginate();

        return view('settings.users.index', compact('users'));
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
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function restore(int $id)
    {
        if (($user = User::onlyTrashed()->find($id)) === null) {
            abort(404);
        }

        $this->authorize('restore', $user);
        $user->restore();

        return redirect()
            ->back()
            ->with('success', __('User unblocked successfully'));
    }

    /**
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function forceDestroy(int $id)
    {
        if (($user = User::withTrashed()->find($id)) === null) {
            abort(404);
        }

        $this->authorize('forceDelete', $user);
        $user->forceDelete();

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
