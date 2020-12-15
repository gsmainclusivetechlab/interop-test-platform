<?php declare(strict_types=1);

namespace App\Http\Client;

use App\Models\TestSetup;
use App\Utils\TokenSubstitution;
use App\Utils\TwigSubstitution;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Response extends \Illuminate\Http\Client\Response implements Arrayable
{
    /**
     * @param  string|null  $key
     * @param  mixed  $default
     * @return array
     */
    public function json($key = null, $default = null)
    {
        return parent::json($key, $default) ?? [];
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
            new \GuzzleHttp\Psr7\Response(
                $data['status'],
                $data['headers'],
                json_encode($data['body'])
            )
        );
    }

    /**
     * @param $testResults
     * @return $this
     */
    public function withVariables($testResults)
    {
        $data = $this->toArray();
        $substitution = new TwigSubstitution($testResults);

        array_walk_recursive($data, function (&$value) use ($substitution) {
            if (is_string($value)) {
                $value = $substitution->replace($value);
            }
        });

        return new self(
            new \GuzzleHttp\Psr7\Response(
                $data['status'],
                $data['headers'],
                json_encode($data['body'])
            )
        );
    }
}
