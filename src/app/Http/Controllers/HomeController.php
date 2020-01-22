<?php

namespace App\Http\Controllers;

use App\Models\TestScenario;
use App\Models\TestScenarioComponent;

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
//        $model = TestScenario::first();
//        $pivot = $model->components()->wherePosition(2)->first()->pivot;
//        dd($model->components);
//        dd($pivot->moveNext());
//        dd($pivot->delete());
//        dd($model->components()->attach([5]));

        return view('home');
    }
}
