<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use App\Http\Resources\MessageLogResource;
use App\Http\Resources\UseCaseResource;
use App\Models\Session;
use App\Models\MessageLog;
use App\Models\UseCase;
use Inertia\Inertia;

class MessageLogController extends Controller
{
    /**
     * TestCaseController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @param Session $session
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function admin()
    {
        $this->authorize('viewAny', MessageLog::class);
        return Inertia::render('admin/message-log/index', [
            'logItems' => MessageLogResource::collection(
                MessageLog::with(['testRun', 'testCase', 'testStep', 'session'])
                    ->latest()
                    ->paginate()
            ),
        ]);
    }

    /**
     * @param Session $session
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
                    'components' => function ($query) {
                        return $query->with(['connections']);
                    },
                ])
            ))->resolve(),
            'useCases' => UseCaseResource::collection(
                UseCase::with([
                    'testCases' => function ($query) use ($session) {
                        $query
                            ->with([
                                'lastTestRun' => function ($query) use (
                                    $session
                                ) {
                                    $query->where('session_id', $session->id);
                                },
                            ])
                            ->whereHas('sessions', function ($query) use (
                                $session
                            ) {
                                $query->whereKey($session->getKey());
                            });
                    },
                ])
                    ->whereHas('testCases', function ($query) use ($session) {
                        $query->whereHas('sessions', function ($query) use (
                            $session
                        ) {
                            $query->whereKey($session->getKey());
                        });
                    })
                    ->get()
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
