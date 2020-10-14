<?php declare(strict_types=1);

namespace App\Http\Client;

use App\Models\TestSetup;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Response extends \Illuminate\Http\Client\Response implements Arrayable
{
    /**
     * @return array
     */
    public function json()
    {
        return parent::json() ?? [];
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
}
