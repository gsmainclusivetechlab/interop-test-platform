<?php declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;
use Inertia\Inertia;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
}
