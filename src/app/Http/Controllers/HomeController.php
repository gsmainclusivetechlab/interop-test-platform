<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use Inertia\Inertia;

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
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('home', [
            'sessions' => SessionResource::collection(
                auth()->user()->sessions()
                    ->with(['testCases', 'lastTestRun'])
                    ->latest()
                    ->paginate(12)
            ),
        ]);
    }
}
