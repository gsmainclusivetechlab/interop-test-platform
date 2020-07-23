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
    public function __invoke()
    {
        return Inertia::render('home', [
            'sessions' => SessionResource::collection(
                auth()
                    ->user()
                    ->sessions()
                    ->with([
                        'testCases' => function ($query) {
                            return $query->with(['useCase', 'lastTestRun']);
                        },
                        'lastTestRun',
                    ])
                    ->latest()
                    ->limit(12)
                    ->get()
            ),
        ]);
    }
}
