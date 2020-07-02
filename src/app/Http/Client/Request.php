<?php declare(strict_types=1);

namespace App\Http\Client;

use App\Models\Component;
use App\Models\Session;
use App\Models\TestSetup;
use DateTime;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
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
     * @param Session $session
     * @return $this
     */
    public function withSubstitutions(Session $session)
    {
        $substitute = function (string &$value) use ($session) {
            if (is_string($value)) {
                /** @noinspection RegExpRedundantEscape */
                $value = preg_replace_callback(
                    '/\\$\{([^\}]+)\}/',
                    function ($matches) use ($session) {
                        switch ($matches[1]) {
                            case 'SP_BASE_URI':
                                return $session->getBaseUriOfComponent(
                                    Component::whereIn('name', [
                                        'Service Provider',
                                    ])->firstOrFail()
                                );
                            case 'FSP1_BASE_URI':
                                return $session->getBaseUriOfComponent(
                                    Component::whereIn('name', [
                                        'Financial Services Provider 1',
                                    ])->firstOrFail()
                                );
                            case 'MOJALOOP_BASE_URI':
                                return $session->getBaseUriOfComponent(
                                    Component::whereIn('name', [
                                        'Mojaloop',
                                    ])->firstOrFail()
                                );
                            case 'FSP2_BASE_URI':
                                return $session->getBaseUriOfComponent(
                                    Component::whereIn('name', [
                                        'Financial Services Provider 2',
                                    ])->firstOrFail()
                                );
                            case 'CURRENT_TIMESTAMP_ISO8601':
                                // used in mobile money API
                                return Carbon::now()->toIso8601String();
                            case 'CURRENT_TIMESTAMP_RFC2822':
                                // used in Mojaloop API
                                return Carbon::now()->toRfc2822String();
                            default:
                                // leave unmatched strings untouched
                                return $matches[0];
                        }
                    },
                    $value
                );
            }
        };

        $data = $this->toArray();
        array_walk_recursive($data, $substitute);

        return new self(
            new ServerRequest(
                $data['method'],
                $data['uri'],
                $data['headers'],
                json_encode($data['body'])
            )
        );
    }

    protected function getDate(?string $time = null): DateTime
    {
        return new DateTime($time);
    }
}
