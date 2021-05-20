<?php declare(strict_types=1);

namespace App\Http\Client;

use App\Models\Session;
use App\Models\TestResult;
use App\Models\TestSetup;
use App\Models\TestStep;
use App\Utils\SimulatorPlugin;
use App\Utils\TwigSubstitution;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Throwable;

class Response extends \Illuminate\Http\Client\Response implements Arrayable
{
    const EMPTY_BODY = 'empty_body';

    /** @var array */
    protected $jws;

    protected $delay;

    public function __construct($request, $jws = null, $delay = 0)
    {
        parent::__construct($request);

        $this->jws = $jws;
        $this->delay = $delay;
    }

    public function jws()
    {
        return $this->jws;
    }

    public function delay()
    {
        return $this->delay;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'status' => $this->status(),
            'delay' => $this->delay(),
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
            $data['jws'],
            $data['delay']
        );
    }

    /**
     * @param $testResults
     * @param Session $session
     * @param TestStep $currentTestStep
     * @return $this
     */
    public function withSubstitutions($testResults, $session, $currentTestStep)
    {
        $data = $this->toArray();
        $substitution = new TwigSubstitution(
            $testResults,
            $session,
            $currentTestStep
        );
        $data = $substitution->replaceRecursive($data);

        return new self(
            new \GuzzleHttp\Psr7\Response(
                $data['status'],
                $data['headers'],
                json_encode($data['body'])
            ),
            $data['jws'],
            $data['delay']
        );
    }

    /**
     * @param TestResult $testResult
     * @return $this
     * @throws Throwable
     */
    public function withPlugin(TestResult $testResult): Response
    {
        if (!$testResult->session->simulatorPlugin) {
            return $this;
        }

        $data = (new SimulatorPlugin($this, $testResult))->process();

        return new self(
            new \GuzzleHttp\Psr7\Response(
                $data['status'] ?? 200,
                $data['headers'] ?? [],
                json_encode($data['body'] ?? '')
            ),
            $data['jws'] ?? null,
            $data['delay'] ?? 0
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
