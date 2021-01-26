<?php declare(strict_types=1);

namespace App\Http\Client;

use App\Models\Session;
use App\Models\TestSetup;
use App\Utils\TwigSubstitution;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Psr\Http\Message\RequestInterface;

class Request extends \Illuminate\Http\Client\Request implements Arrayable
{
    const EMPTY_BODY = 'empty_body';

    /** @var array */
    protected $jws;

    public function __construct($request, $jws = null)
    {
        parent::__construct($request);

        $this->jws = $jws;
    }

    public function urlForResolver()
    {
        return $this->host() ? $this->url() : ltrim($this->path(), '/');
    }

    public function path(): string
    {
        return $this->request->getUri()->getPath();
    }

    public function jws()
    {
        return $this->jws;
    }

    public function host(): string
    {
        return $this->request->getUri()->getHost();
    }

    public function query(): string
    {
        return $this->request->getUri()->getQuery();
    }

    public function toPsrRequest(): RequestInterface
    {
        return $this->request;
    }

    public function toArray(): array
    {
        return [
            'method' => $this->method(),
            'uri' => $this->url(),
            'path' => $this->path(),
            'headers' => $this->headers(),
            'body' => $this->json(),
            'jws' => $this->jws(),
        ];
    }

    /**
     * @param TestSetup $setup
     * @return $this
     */
    public function withSetup(TestSetup $setup)
    {
        $data = $this->toArray();

        foreach ($setup->values as $key => $value) {
            Arr::set($data, $key, $value);
        }

        return new self(
            new ServerRequest(
                $data['method'],
                $data['uri'],
                $data['headers'],
                json_encode($data['body'])
            ),
            $data['jws']
        );
    }

    /**
     * @param $testResults
     * @param Session $session
     * @return $this
     */
    public function withSubstitutions($testResults, $session)
    {
        $data = $this->toArray();
        $data['uri'] = rawurldecode($data['uri']);

        $substitution = new TwigSubstitution($testResults, $session);
        $data = $substitution->replaceRecursive($data);

        return new self(
            new ServerRequest(
                $data['method'],
                $data['uri'],
                $data['headers'],
                json_encode($data['body'])
            ),
            $data['jws']
        );
    }

    /**
     * @return bool
     */
    public function isEmptyBody(): bool
    {
        return static::EMPTY_BODY === $this->json();
    }
}
