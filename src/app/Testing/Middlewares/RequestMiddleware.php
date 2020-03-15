<?php declare(strict_types=1);

namespace App\Testing\Middlewares;

use App\Models\TestRequestSetup;
use Illuminate\Support\Arr;
use Psr\Http\Message\RequestInterface;
use function GuzzleHttp\Psr7\stream_for;

class RequestMiddleware
{
    /**
     * @var TestRequestSetup
     */
    protected $setup;

    /**
     * @param TestRequestSetup $setup
     */
    public function __construct(TestRequestSetup $setup)
    {
        $this->setup = $setup;
    }

    /**
     * @param callable $handler
     * @return callable
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            return $handler($this->modifyRequest($request), $options);
        };
    }

    protected function modifyRequest(RequestInterface $request)
    {
        $contents = json_decode($request->getBody()->__toString(), true);

        foreach (Arr::get($this->setup->values, 'body', []) as $key => $value) {
            Arr::set($contents, $key, $value);
        }

        $stream = stream_for(json_encode($contents));
        $request = $request->withBody($stream);

        foreach (Arr::get($this->setup->values, 'headers', []) as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        return $request;
    }
}
