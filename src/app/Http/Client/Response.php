<?php declare(strict_types=1);

namespace App\Http\Client;

use App\Models\Session;
use App\Models\TestSetup;
use App\Utils\TwigSubstitution;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Response extends \Illuminate\Http\Client\Response implements Arrayable
{
    const EMPTY_BODY = 'empty_body';

    /** @var array */
    protected $jws;

    public function __construct($request, $jws = null)
    {
        parent::__construct($request);

        $this->jws = $jws;
    }

    public function jws()
    {
        return $this->jws;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'status' => $this->status(),
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
            new \GuzzleHttp\Psr7\Response(
                $data['status'],
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
        $substitution = new TwigSubstitution($testResults, $session);
        $data = $substitution->replaceRecursive($data);

        return new self(
            new \GuzzleHttp\Psr7\Response(
                $data['status'],
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
