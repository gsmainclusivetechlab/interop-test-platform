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
}
