<?php declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;
use Inertia\Inertia;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if (in_array($response->status(), [
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
                'status' => $response->status(),
            ])->toResponse($request);
        }

        return $response;
    }
}
