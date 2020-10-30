<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
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
                    ->with([
                        'owner',
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
