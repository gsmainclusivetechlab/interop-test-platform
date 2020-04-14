<?php declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
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
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param Throwable $e
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    protected function toIlluminateResponse($response, Throwable $e)
    {
        if ($response instanceof InertiaResponse) {
            return $response;
        } else {
            return parent::toIlluminateResponse($response, $e);
        }
    }

    /**
     * @param HttpExceptionInterface $e
     * @return \Inertia\Response|\Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        if (in_array($e->getStatusCode(), [
            400,
            401,
            403,
            404,
            419,
            429,
            500,
            503,
        ])) {
            return Inertia::render("errors/{$e->getStatusCode()}", [
                'exception' => [
                    'code' => $e->getStatusCode(),
                    'message' => $e->getMessage(),
                ],
            ]);
        }

        return $this->convertExceptionToResponse($e);
    }
}
