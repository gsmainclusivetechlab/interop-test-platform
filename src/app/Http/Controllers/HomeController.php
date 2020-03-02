<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Symfony\Component\Yaml\Yaml;

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
//        dd(Yaml::parseFile(database_path('seeds/data/test-scenarios.yaml')));
        $sessions = auth()->user()->sessions()
            ->latest()
            ->paginate(12);

        return view('home', compact('sessions'));
    }
}
