<?php

namespace App\Http\Controllers;

use App\Facades\Actor;

class HomeController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $actor = Actor::driver('mojaloop');
        dd($actor);

        return view('home');
    }
}
