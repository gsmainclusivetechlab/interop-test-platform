<?php declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Client\Request;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * @param HttpExceptionInterface $e
     * @return \Illuminate\Http\JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        if (in_array($e->getStatusCode(), [
                400,
                401,
                403,
                404,
                405,
                419,
                429,
                500,
                503,
            ])) {
            return Inertia::render('error', [
                'status' => $e->getStatusCode(),
            ])->toResponse(request());
        }

        return $this->renderHttpException($e);
    }

    /**
     * @param Throwable $e
     * @throws \Exception
     */
    public function report(Throwable $e)
    {
        if ($e instanceof TestMismatchException) {
            $this->reportTestMismatch($e);
        }

        parent::report($e);
    }

    /**
     * @param TestMismatchException $e
     */
    protected function reportTestMismatch(TestMismatchException $e)
    {
        $e->getSession()->testMismatches()->create([
            'exception' => $e->getMessage(),
            'request' => new Request(new ServerRequest(
                request()->method(),
                request()->url(),
                request()->headers->all(),
                request()->getContent()
            )),
        ]);
    }
}
