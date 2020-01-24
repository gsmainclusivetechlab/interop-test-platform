<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        return view('account.profile.edit');
    }


    public function update()
    {

    }
}
