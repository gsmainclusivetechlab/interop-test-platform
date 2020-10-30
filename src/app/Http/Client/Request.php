<?php declare(strict_types=1);

namespace App\Http\Client;

use App\Models\TestSetup;
use App\Utils\TokenSubstitution;
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
        return $this->request->getUri()->getPath();
    }

    /**
     * @return array
     */
    public function json()
    {
        return parent::json() ?? [];
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
     * @return $this
     */
    public function withSubstitutions(array $tokens = [])
    {
        $data = $this->toArray();
        $substitution = new TokenSubstitution($tokens);
        array_walk_recursive($data, function (&$value) use ($substitution) {
            if (is_string($value)) {
                $value = $substitution->replace($value);
            }
        });

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
