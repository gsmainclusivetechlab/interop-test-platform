<?php

namespace App\Testing\Tests;

use App\Models\TestResult;
use App\Testing\TestCase;
use Arr;
use File;
use Gamegos\JWS\JWS;
use Gamegos\JWS\Util\Base64Url;
use Gamegos\JWS\Util\Json;

class ResponseJwsValidationTest extends TestCase
{
    /** @var TestResult */
    protected $testResult;

    /** @var array */
    protected $jwsData;

    /** @var JWS */
    protected $jws;

    public function __construct(TestResult $testResult, $jws)
    {
        $this->testResult = $testResult;
        $this->jwsData = $jws;
        $this->jws = new JWS();
    }

    public function getName(): string
    {
        return 'Response: JWS Signature';
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

            $token = sprintf(
                '%s.%s.%s',
                Arr::get($header, 'protectedHeader'),
                Base64Url::encode(
                    Json::encode($this->testResult->request->data())
                ),
                Arr::get($header, 'signature')
            );
        }

        $this->jws->verify($token, $this->getKey());
    }

    protected function getHeader(): string
    {
        return $this->testResult->response->header($this->getHeaderName());
    }

    protected function getHeaderName(): string
    {
        return Arr::get($this->jwsData, 'header', 'FSPIOP-Signature');
    }

    protected function getKey(): string
    {
        return File::exists($filePath = Arr::get($this->jwsData, 'key'))
            ? File::get($filePath)
            : $filePath;
    }

    protected function isMojaloop()
    {
        return Arr::get($this->jwsData, 'transform') === 'mojaloop';
    }
}
