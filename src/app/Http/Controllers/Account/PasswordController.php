<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }


    public function edit()
    {
        return view('account.password.edit');
    }


    public function update()
    {

    }
}
