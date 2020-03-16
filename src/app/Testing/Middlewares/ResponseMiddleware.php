<?php declare(strict_types=1);

namespace App\Testing\Middlewares;

use App\Models\TestResponseSetup;
use Illuminate\Support\Arr;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;

class ResponseMiddleware
{
    /**
     * @var TestResponseSetup
     */
    protected $setup;

    /**
     * @param TestResponseSetup $setup
     */
    public function __construct(TestResponseSetup $setup)
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
            return $handler($request, $options)->then(function (ResponseInterface $response) {
                return $this->modifyResponse($response);
            });
        };
    }

    protected function modifyResponse(ResponseInterface $response)
    {
        $response->withStatus(Arr::get($this->setup->values, 'status', $response->getStatusCode()));
        $contents = json_decode($response->getBody()->__toString(), true);

        foreach (Arr::get($this->setup->values, 'body', []) as $key => $value) {
            Arr::set($contents, $key, $value);
        }

        $stream = stream_for(json_encode($contents));
        $response = $response->withBody($stream);

        foreach (Arr::get($this->setup->values, 'headers', []) as $key => $value) {
            $response = $response->withHeader($key, $value);
        }

        return $response;
    }
}
