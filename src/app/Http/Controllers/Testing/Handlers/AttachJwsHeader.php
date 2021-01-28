<?php

namespace App\Http\Controllers\Testing\Handlers;

use App\Http\Client\Request;
use App\Models\TestResult;
use Arr;
use File;
use Gamegos\JWS\JWS;
use Gamegos\JWS\Util\Base64Url;
use Gamegos\JWS\Util\Json;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\MessageInterface;

class AttachJwsHeader
{
    /** @var TestResult */
    protected $testResult;

    /** @var Request|Response */
    protected $jwsSource;

    public function __construct(TestResult $testResult, $jwsSource)
    {
        $this->testResult = $testResult;
        $this->jwsSource = $jwsSource;
    }

    public function __invoke(MessageInterface $requestOrResponse)
    {
        if ($this->jwsSource->jws()) {
            $jws = $this->jwsSource
                ->withSubstitutions(
                    $this->testResult->testRun->testResults,
                    $this->testResult->session
                )
                ->jws();

            $headerName = Arr::get($jws, 'header', 'FSPIOP-Signature');
            $filePath = Arr::get($jws, 'key');

            if (
                !$requestOrResponse->hasHeader($headerName) &&
                !is_null($filePath)
            ) {
                $key = File::exists($filePath)
                    ? File::get($filePath)
                    : $filePath;

                $headers = collect(Arr::get($jws, 'protectedHeaders'))
                    ->mapWithKeys(function ($header) use ($requestOrResponse) {
                        return [
                            $header => $requestOrResponse->getHeaderLine(
                                $header
                            ),
                        ];
                    })
                    ->prepend(Arr::get($jws, 'alg', 'RS256'), 'alg')
                    ->all();

                $body = json_decode(
                    (string) $requestOrResponse->getBody(),
                    true
                );

                $jwsEncoder = new JWS();
                $signature = $jwsEncoder->encode($headers, $body, $key);

                if (Arr::get($jws, 'transform') === 'mojaloop') {
                    $signature = Json::encode([
                        'signature' =>
                            substr($signature, strrpos($signature, '.') + 1) ?:
                            '',
                        'protectedHeader' => Base64Url::encode(
                            Json::encode($headers)
                        ),
                    ]);
                }

                $requestOrResponse = $requestOrResponse->withHeader(
                    $headerName,
                    $signature
                );
            }
        }

        return $requestOrResponse;
    }
}
