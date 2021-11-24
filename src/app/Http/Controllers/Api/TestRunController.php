<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TestRunResource;
use App\Models\TestRun;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

class TestRunController extends Controller
{
    /**
     * @param TestRun $testRun
     * @return Response
     * @throws AuthorizationException
     */
    public function status(TestRun $testRun): Response
    {
        $this->authorize('owner', $testRun->session);

        return response(
            (new TestRunResource($testRun))->resolve(),
            201
        );
    }

    /**
     * @param TestRun $testRun
     * @return Response
     * @throws AuthorizationException
     */
    public function complete(TestRun $testRun): Response
    {
        $this->authorize('owner', $testRun->session);

        if (!$testRun->isCompleted()) {
            $testRun->complete();
        }

        return response(
            (new TestRunResource($testRun))->resolve(),
            201
        );
    }
}
