<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\Session;
use Illuminate\Database\Eloquent\Builder;
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
                Session::whereHas('owner', function (Builder $query) {
                    $query
                        ->whereKey(auth()->user())
                        ->orWhereHas('groups', function (Builder $query) {
                            $query->whereHas('members', function (
                                Builder $query
                            ) {
                                $query->whereKey(auth()->user());
                            });
                        });
                })
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
