<?php

namespace App\Http\Controllers\Sessions;

use App\Models\TestSession;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function createSelection()
    {
        return view('sessions.register.selection');
    }

    public function createConfiguration()
    {
        return view('sessions.register.configuration');
    }

    public function createInformation()
    {
        return view('sessions.register.information');
    }
}
