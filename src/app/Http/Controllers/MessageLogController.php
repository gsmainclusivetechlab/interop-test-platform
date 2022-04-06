<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Http\Resources\MessageLogResource;
use App\Http\Resources\UseCaseResource;
use App\Models\Session;
use App\Models\UseCase;
use Illuminate\Auth\Access\AuthorizationException;
use Inertia\Inertia;
use Inertia\Response;

class MessageLogController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @param Session $session
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Session $session)
    {
        $this->authorize('view', $session);

        return Inertia::render('sessions/message-log/index', [
            'session' => (new SessionResource(
                $session->load([
                    'testCases' => function ($query) {
                        return $query->with(['useCase', 'lastTestRun']);
                    },
                ])
            ))->resolve(),
            'useCases' => UseCaseResource::collection(
                UseCase::withTestCasesOfSession($session)->get()
            ),
            'logItems' => MessageLogResource::collection(
                $session
                    ->messageLog()
                    ->with(['testRun', 'testCase', 'testStep'])
                    ->latest()
                    ->paginate()
            ),
        ]);
    }
}
