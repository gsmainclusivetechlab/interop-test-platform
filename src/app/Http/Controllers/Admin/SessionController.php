<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\SessionResource;
use App\Models\Session;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SessionController extends Controller
{
    /**
     * SessionController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(Session::class, 'session');
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('admin/sessions/index', [
            'sessions' => SessionResource::collection(
                Session::whereHas('owner', function (Builder $query) {
                    $query->when(request('q'), function (Builder $query, $q) {
                        $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$q}%")
                            ->orWhere('name', 'like', "%{$q}%");
                    });
                })
                    ->with([
                        'owner',
                        'testCases' => function ($query) {
                            return $query->with(['lastTestRun']);
                        },
                        'lastTestRun',
                    ])
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }
}
