<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TestCaseResource;
use App\Http\Resources\TestMismatchResource;
use App\Http\Resources\UseCaseResource;
use App\Jobs\ExecuteTestRunJob;
use App\Models\TestCase;
use App\Models\Session;
use App\Models\UseCase;
use Inertia\Inertia;

class TestMismatchController extends Controller
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
    public function index(Session $session)
    {
        $this->authorize('view', $session);

        return Inertia::render('sessions/test-mismatches/index', [
            'session' => (new SessionResource(
                $session->load([
                    'testCases' => function ($query) {
                        return $query->with(['lastTestRun']);
                    },
                    'components' => function ($query) {
                        return $query->with(['connections']);
                    },
                ])
            ))->resolve(),
            'useCases' => UseCaseResource::collection(
                UseCase::with([
                    'testCases' => function ($query) use($session) {
                        $query->with([
                            'lastTestRun' => function ($query) use ($session) {
                                $query->where('session_id', $session->id);
                            },
                        ])->whereHas('sessions', function ($query) use($session) {
                            $query->whereKey($session->getKey());
                        });
                    }])
                    ->whereHas('testCases', function ($query) use($session) {
                        $query->whereHas('sessions', function ($query) use($session) {
                            $query->whereKey($session->getKey());
                        });
                    })
                    ->get()
            ),
            'testMismatches' => TestMismatchResource::collection(
                $session->testMismatches()
                    ->latest()
                    ->paginate()
            ),
        ]);
    }
}
