<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\TestSession;

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
        $sessions = auth()->user()->sessions()
            ->when(request('q'), function ($query, $q) {
                return $query->where('name', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate();

        return view('sessions.index', compact('sessions'));
    }

    /**
     * @param TestSession $session
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TestSession $session)
    {
        $runs = $session->runs()
            ->with('case', 'session')
            ->whereNotNull('completed_at')
            ->latest()
            ->paginate();

        return view('sessions.show', compact('session', 'runs'));
    }
}
