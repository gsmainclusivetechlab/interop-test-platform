<?php

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use Arr;
use File;
use Gamegos\JWS\JWS;
use Gamegos\JWS\Util\Base64Url;
use Gamegos\JWS\Util\Json;
use GuzzleHttp\Psr7\ServerRequest;

class AttachJwsHeader
{
    /** @var TestResult */
    protected $testResult;

    public function __construct(TestResult $testResult)
    {
        $this->testResult = $testResult;
    }

    public function __invoke(ServerRequest $request): ServerRequest
    {
        if ($this->testResult->testStep->request->jws()) {
            $jws = $this->testResult->testStep->request
                ->withSubstitutions(
                    $this->testResult->testRun->testResults,
                    $this->testResult->session
                )
                ->jws();

            $key = File::exists($filePath = Arr::get($jws, 'key'))
                ? File::get($filePath)
                : $filePath;

            $headers = collect(Arr::get($jws, 'protectedHeaders'))
                ->mapWithKeys(function ($header) use ($request) {
                    return [$header => $request->getHeaderLine($header)];
                })
                ->prepend(Arr::get($jws, 'alg', 'RS256'), 'alg')
                ->all();

            $body = json_decode((string) $request->getBody(), true);

            $jwsEncoder = new JWS();
            $signature = $jwsEncoder->encode($headers, $body, $key);

            if (Arr::get($jws, 'transform') === 'mojaloop') {
                $signature = Json::encode([
                    'signature' =>
                        substr($signature, strrpos($signature, '.') + 1) ?: '',
                    'protectedHeader' => Base64Url::encode(
                        Json::encode($headers)
                    ),
                ]);
            }

            $request = $request->withHeader(
                Arr::get($jws, 'header', 'FSPIOP-Signature'),
                $signature
            );
        }

        return $request;
    }
}
