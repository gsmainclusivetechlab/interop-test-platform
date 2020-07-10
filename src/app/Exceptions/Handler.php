<?php declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Client\Request;
use App\Models\MessageMismatch;
use GuzzleHttp\Psr7\ServerRequest;
use Inertia\Inertia;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    protected $dontFlash = ['password', 'password_confirmation'];

    /**
     * @param HttpExceptionInterface $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        if (
            in_array($e->getStatusCode(), [
                400,
                401,
                403,
                404,
                405,
                419,
                429,
                500,
                503,
            ])
        ) {
            return Inertia::render('error', [
                'status' => $e->getStatusCode(),
            ])->toResponse(request());
        }

        return parent::renderHttpException($e);
    }

    private function coerceToMessageMismatch(Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $ids = $e->getIds();
            $ids = json_encode(is_int($ids) ? [$ids] : $ids);
            switch ($e->getModel()) {
                case 'App\\Models\\Session':
                    $e = new MessageMismatchException(
                        null,
                        404,
                        "Unable to find a session in $ids. Please check the request URL."
                    );
                    break;
                case 'App\\Models\\Component':
                    $e = new MessageMismatchException(
                        null,
                        404,
                        "Unable to find a component in $ids. Please check the request URL."
                    );
                    break;
            }
        }

        return $e;
    }

    /**
     * @param Throwable $e
     * @throws \Exception
     */
    public function report(Throwable $e)
    {
        $e = $this->coerceToMessageMismatch($e);

        if ($e instanceof MessageMismatchException) {
            $this->reportMessageMismatch($e);
        }
        if ($e instanceof NotFoundHttpException) {
            $this->reportMessageMismatch(
                new MessageMismatchException(
                    null,
                    404,
                    'Attempt to call non-existent path'
                )
            );
        }

        parent::report($e);
    }

    /**
     * @param MessageMismatchException $e
     */
    protected function reportMessageMismatch(MessageMismatchException $e)
    {
        $request = request();
        $mismatch = new MessageMismatch([
            'exception' => $e->getMessage(),
            'request' => new Request(
                new ServerRequest(
                    $request->method(),
                    $request->url(),
                    $request->headers->all(),
                    $request->getContent()
                )
            ),
        ]);
        $session = $e->getSession();
        if ($session != null) {
            $mismatch->session()->associate($session);
        }

        $mismatch->save();
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */

    public function render($request, Throwable $e)
    {
        $e = $this->coerceToMessageMismatch($e);

        return parent::render($request, $e);
    }
}
