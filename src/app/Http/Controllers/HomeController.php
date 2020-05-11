<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\Component;
use App\Models\Specification;
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
        dd(Specification::latest()->first()->openapi);
//        dd(Component::first()->connections()->first()->pivot->specification);

//        $endpoint = ApiEndpoint::where('method', 'PUT')
//            ->whereRaw('REGEXP_LIKE(?, CONCAT("^", REGEXP_REPLACE(route, "\\\{(.*?)\\\}", "[[:alnum:]]|[[:punct]]"), "+$"))', ['transactionRequests/as2-das'])
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
