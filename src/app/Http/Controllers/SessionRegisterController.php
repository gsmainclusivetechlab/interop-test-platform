<?php

namespace App\Http\Controllers;

use App\Models\TestSession;

class SessionRegisterController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function showSelections()
    {
        return view('sessions.register.selections');
    }

    public function createSelection()
    {
        return view('sessions.register.create-selection');
    }

    public function createForwardConfiguration()
    {
        return view('sessions.register.create-forward-configuration');
    }

    public function createBackwardConfiguration()
    {
        return view('sessions.register.create-backward-configuration');
    }

    public function createInformation()
    {
        return view('sessions.register.create-information');
    }
}
