<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\TestSession;

class RegisterController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        return view('sessions.register.index');
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
