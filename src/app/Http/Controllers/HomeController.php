<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\Component;
use App\Models\Session;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;

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
     * @return Response
     */
    public function __invoke()
    {
//        $components = Component::all()->load('connections');
//        $session = Session::whereKey(6)->first();dd($session->components()->withPivotValue('use_encryption', true)->pluck('id'));
//        dd($components
//            ->mapWithKeys(function (Component $item) use (
//                $session
//            ) {
//                $sessionComponents = $session->components();
//                $connectionUrls = [];
//                foreach ($item->connections as $connection) {
//                    $connectionUrls[$item->slug][$connection->slug] = route(
//                        'testing.sut',
//                        [
//                            $session->uuid,
//                            $item->uuid,
//                            $connection->uuid,
//                        ]
//                    );
//                }
//
//                return $connectionUrls;
//            })
//            ->toArray()
//        );
        return Inertia::render('home', [
            'sessions' => SessionResource::collection(
                Session::whereHas('owner', function (Builder $query) {
                    $query
                        ->whereKey(
                            auth()
                                ->user()
                                ->getAuthIdentifier()
                        )
                        ->orWhereHas('groups', function (Builder $query) {
                            $query->whereHas('users', function (
                                Builder $query
                            ) {
                                $query->whereKey(
                                    auth()
                                        ->user()
                                        ->getAuthIdentifier()
                                );
                            });
                        });
                })
                    ->with(['owner', 'lastTestRun'])
                    ->latest()
                    ->limit(12)
                    ->get()
            ),
        ]);
    }
}
