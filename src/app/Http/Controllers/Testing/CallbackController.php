<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
use App\Models\Specification;
use App\Models\TestRun;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CallbackController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api', SetJsonHeaders::class, ValidateTraceContext::class]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param string|null $path
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, Specification $specification, string $path = null)
    {
        $traceparent = new TraceparentHeader($request->getHeaderLine(TraceparentHeader::NAME));
        $run = TestRun::whereRaw('REPLACE(uuid, "-", "") = ?', $traceparent->getTraceId())
            ->firstOrFail();
        $step = $run->steps()
            ->whereRaw('? like path', $path)
            ->where('method', $request->getMethod())
            ->whereHas('platform', function ($query) use ($specification) {
                $query->where('specification_id', $specification->id);
            })
            ->firstOrFail();

        $uri = (new Uri($step->platform->server))
            ->withPath($path);
        $request = $request->withUri($uri);
        $response = (new Client(['http_errors' => false]))->send($request);

        return $response;
    }
}
