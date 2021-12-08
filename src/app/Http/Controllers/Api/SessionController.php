<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TestCaseResource;
use App\Jobs\ExecuteTestRunJob;
use App\Models\Session;
use App\Models\TestCase;
use Arr;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

class SessionController extends Controller
{
    /**
     * @param Session $session
     * @return Response
     * @throws AuthorizationException
     */
    public function testCases(Session $session): Response
    {
        $this->authorize('owner', $session);

        $testCases = TestCaseResource::collection($session->getTestCasesExecuteAvailableWithoutSutInitiator())
            ->resolve();
        if (!$testCases) {
            $content = ['message' => 'No available Test Cases'];
            return response($content, 404);
        }

        return response($testCases);
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @return Response
     * @throws AuthorizationException
     */
    public function run(Session $session, TestCase $testCase): Response
    {
        $this->authorize('owner', $session);

        $availableIds = Arr::pluck($session->getTestCasesExecuteAvailableWithoutSutInitiator(), 'id');
        if (!in_array($testCase->id, $availableIds)) {
            $content = ['message' => 'Test case is not available to run for this session'];
            return response($content, 403);
        }

        $testRun = $session
            ->testRuns()
            ->create(['test_case_id' => $testCase->id]);
        ExecuteTestRunJob::dispatch($testRun)->afterResponse();

        return response(
            $testRun->only(['id', 'uuid', 'session_id', 'test_case_id']),
            201
        );
    }
}
