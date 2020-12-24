<?php declare(strict_types=1);

namespace App\Http\Client;

use App\Models\TestSetup;
use App\Utils\TwigSubstitution;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Psr\Http\Message\RequestInterface;

class Request extends \Illuminate\Http\Client\Request implements Arrayable
{
    /**
     * @return string
     */
    public function path()
    {
        return (string) $this->request->getUri();
    }

    /**
     * @return RequestInterface
     */
    public function toPsrRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'method' => $this->method(),
            'uri' => $this->url(),
            'path' => $this->path(),
            'headers' => $this->headers(),
            'body' => $this->json(),
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
            )
        );
    }

    /**
     * @param array|null $tokens
     * @param $testResults
     * @return $this
     */
    public function withSubstitutions($testResults, array $tokens = [])
    {
        $data = $this->toArray();
        $data['uri'] = urldecode($data['uri']);
        $substitution = new TwigSubstitution($testResults, $tokens);
        $data = $substitution->replaceRecursive($data);

        return new self(
            new ServerRequest(
                $data['method'],
                $data['uri'],
                $data['headers'],
                json_encode($data['body'])
            )
        );
    }
}
