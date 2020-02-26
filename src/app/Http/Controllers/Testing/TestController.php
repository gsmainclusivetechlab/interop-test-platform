<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
use App\Models\Specification;
use App\Models\TestRun;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Database\Query\Builder;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class TestController extends Controller
{
    /**
     * TestController constructor.
     */
    public function __construct()
    {
        $this->middleware(['api', SetJsonHeaders::class, ValidateTraceContext::class]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Specification $specification
     * @param string|null $path
     * @return \Exception|AssertionFailedError|ResponseInterface|Throwable
     */
    public function __invoke(ServerRequestInterface $request, Specification $specification, string $path = null)
    {
        $traceparent = new TraceparentHeader($request->getHeaderLine(TraceparentHeader::NAME));
        $run = TestRun::whereRaw('REPLACE(uuid, "-", "") = ?', $traceparent->getTraceId())
            ->firstOrFail();
        $step = $run->steps()
            ->whereRaw('? like path', $path)
            ->where('method', $request->getMethod())
            ->whereHas('platform', function (Builder $query) use ($specification) {
                $query->where('specification_id', $specification->id);
            })
            ->firstOrFail();

        $uri = (new Uri($step->platform->server))
            ->withPath($path);
        $request = $request->withUri($uri);

        return $this->doTest($request, $run, $step);
    }
}
