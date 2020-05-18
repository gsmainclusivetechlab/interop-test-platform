<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\TestStep;
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
//        $endpoint = TestStep::whereRaw('REGEXP_LIKE(?, pattern)', ['http://172.16.14.101:8084/7.x/middleware'])
//            ->get();
//
//        dd($endpoint);

        return Inertia::render('home', [
            'sessions' => SessionResource::collection(
                auth()->user()->sessions()
                    ->with([
                        'testCases' => function ($query) {
                            return $query->with(['lastTestRun']);
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
