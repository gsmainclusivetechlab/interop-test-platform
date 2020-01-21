<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;

class UserController extends Controller
{
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
     */
    public function trashed()
    {
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
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function restore(User $user)
    {
        $user->restore();

        return redirect()
            ->route('settings.users.trashed')
            ->with('success', __('User restored successfully'));
    }
}
