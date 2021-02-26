<?php

namespace App\Testing\Tests;

use App\Http\Client\Request;
use App\Http\Client\Response;
use App\Testing\TestCase;
use Arr;
use File;
use Gamegos\JWS\Exception\MalformedSignatureException;
use Gamegos\JWS\JWS;
use Gamegos\JWS\Util\Base64Url;
use App\Utils\JWS\Json;
use Illuminate\Support\Collection;

class JwsValidationTest extends TestCase
{
    /** @var Request|Response */
    protected $requestOrResponse;

    /** @var array */
    protected $jwsData;

    /** @var JWS */
    protected $jws;

    /** @var string */
    protected $title;

    /** @var Collection */
    protected $headers;

    /**
     * JwsValidationTest constructor.
     *
     * @param Request|Response $requestOrResponse
     * @param array $jwsData
     * @param string $title
     */
    public function __construct($requestOrResponse, $jwsData, string $title)
    {
        $this->requestOrResponse = $requestOrResponse;
        $this->jwsData = $jwsData;
        $this->jws = new JWS();
        $this->title = $title;

        $this->headers = collect(
            $this->requestOrResponse->headers()
        )->mapWithKeys(function ($header, $headerName) {
            return [strtolower($headerName) => $header[0]];
        });
    }

    public function getName(): string
    {
        return $this->title;
    }

    public function getActual(): array
    {
        return [
            $this->getHeaderName() => $this->getHeader(),
        ];
    }

    public function getExpected(): array
    {
        return [
            $this->getHeaderName() => $this->isMojaloop()
                ? Json::encode([
                    'signature' => '...',
                    'protectedHeader' => '...',
                ])
                : 'protectedHeader.payload.signature',
        ];
    }

    protected function test()
    {
        $jwsString = $this->getHeader();
        $data = Arr::get($this->requestOrResponse->toArray(), 'body');

        if ($this->isMojaloop()) {
            $header = Json::decode($jwsString);

            $protectedHeader = Arr::get($header, 'protectedHeader');
            $signature = Arr::get($header, 'signature');
        } else {
            $components = explode('.', $jwsString);
            if (count($components) !== 3) {
                throw new MalformedSignatureException(
                    'JWS string must contain 3 dot separated component.'
                );
            }

            [$protectedHeader, , $signature] = $components;
        }

        $token = sprintf(
            '%s.%s.%s',
            $protectedHeader,
            Base64Url::encode(Json::encode($data)),
            $signature
        );

        $this->jws->verify($token, $this->getKey());

        collect($this->jws->decode($token)['headers'])
            ->forget('alg')
            ->each(function ($protectedHeaderValue, $headerName) {
                $httpHeaderValue = $this->headers->get(strtolower($headerName));

                if ($httpHeaderValue !== $protectedHeaderValue) {
                    throw new \Exception(
                        __(
                            "{$headerName} HTTP header value: '{$httpHeaderValue}' does not match protected header value: '{$protectedHeaderValue}'"
                        )
                    );
                }
            });
    }

    protected function getHeader(): string
    {
        $neededHeader = strtolower($this->getHeaderName());

        return $this->headers->get($neededHeader, '');
    }

    protected function getHeaderName(): string
    {
        return Arr::get($this->jwsData, 'header', 'FSPIOP-Signature');
    }

    protected function getKey(): string
    {
        return File::exists($filePath = Arr::get($this->jwsData, 'public_key'))
            ? File::get($filePath)
            : $filePath;
    }

    protected function isMojaloop(): bool
    {
        return Arr::get($this->jwsData, 'transform') === 'mojaloop';
    }
}
