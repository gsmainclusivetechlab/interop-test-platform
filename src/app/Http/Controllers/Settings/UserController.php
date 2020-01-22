<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;
use App\Http\Controllers\Controller;

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
        $users = User::latest()->paginate();

        return view('settings.users.index', compact('users'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function trashed()
    {
        $this->authorize('viewAny', User::class);
        $users = User::latest()->onlyTrashed()->paginate();

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
            ->route('settings.users.index')
            ->with('success', __('User deleted successfully'));
    }

    /**
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function restore(int $id)
    {
        $user = $this->findOnlyTrashedOrFail($id);
        $this->authorize('restore', $user);
        $user->restore();

        return redirect()
            ->route('settings.users.trashed')
            ->with('success', __('User restored successfully'));
    }

    /**
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function forceDestroy(int $id)
    {
        $user = $this->findOnlyTrashedOrFail($id);
        $this->authorize('forceDelete', $user);
        $user->forceDelete();

        return redirect()
            ->route('settings.users.trashed')
            ->with('success', __('User force deleted successfully'));
    }

    /**
     * @param int $id
     * @return User|null
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function findOnlyTrashedOrFail(int $id)
    {
        $user = User::onlyTrashed()->find($id);

        if ($user === null) {
            abort(404);
        } else {
            return $user;
        }
    }
}
