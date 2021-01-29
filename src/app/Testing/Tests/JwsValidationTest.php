<?php

namespace App\Testing\Tests;

use App\Http\Client\Request;
use App\Http\Client\Response;
use App\Testing\TestCase;
use Arr;
use File;
use Gamegos\JWS\JWS;
use Gamegos\JWS\Util\Base64Url;
use Gamegos\JWS\Util\Json;

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
        $token = $this->getHeader();

        if ($this->isMojaloop()) {
            $header = Json::decode($token);
            $data = Arr::get($this->requestOrResponse->toArray(), 'body');

            $token = sprintf(
                '%s.%s.%s',
                Arr::get($header, 'protectedHeader'),
                Base64Url::encode(Json::encode($data)),
                Arr::get($header, 'signature')
            );
        }

        $this->jws->verify($token, $this->getKey());
    }

    protected function getHeader(): string
    {
        $neededHeader = strtolower($this->getHeaderName());

        $result = collect($this->requestOrResponse->headers())->first(function (
            $header,
            $headerName
        ) use ($neededHeader) {
            return $neededHeader == strtolower($headerName);
        },
        '');

        return is_array($result) ? $result[0] : $result;
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

    protected function isMojaloop()
    {
        return Arr::get($this->jwsData, 'transform') === 'mojaloop';
    }
}
