<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\SessionResource;
use App\Models\Session;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;

class SessionController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(Session::class, 'session', [
            'only' => ['index'],
        ]);
    }

    /**
     * @return Response
     */
    public function index()
    {
        return Inertia::render('admin/sessions/index', [
            'sessions' => SessionResource::collection(
                Session::whereHas('owner', function (Builder $query) {
                    $query->when(request('q'), function (Builder $query, $q) {
                        $query
                            ->whereRaw(
                                'CONCAT(first_name, " ", last_name) like ?',
                                "%{$q}%"
                            )
                            ->orWhere('name', 'like', "%{$q}%");
                    });
                })
                    ->with([
                        'owner',
                        'testCases' => function ($query) {
                            return $query->with(['useCase', 'lastTestRun']);
                        },
                        'lastTestRun',
                    ])
                    ->where('type', Session::TYPE_TEST)
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }
}
