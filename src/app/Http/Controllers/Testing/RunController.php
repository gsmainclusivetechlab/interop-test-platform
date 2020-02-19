<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Middleware\SetJsonHeaders;
use App\Models\Environment;
use App\Models\TestSessionCase;
use App\Testing\TestRunner;
use App\Testing\Tests\ValidateRequestTest;
use App\Testing\Tests\ValidateResponseTest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use PHPUnit\Framework\TestSuite;
use Psr\Http\Message\ServerRequestInterface;

class RunController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api', SetJsonHeaders::class]);
    }

    public function __invoke(TestSessionCase $sessionCase, ServerRequestInterface $request, string $path)
    {
        $environment = Environment::first();
        $step = $sessionCase->steps()
            ->where('path', $path)
            ->where('method', $request->getMethod())
            ->whereHas('targetSpecification')
            ->first();

        if ($step === null) {
            abort(404);
        }



        $uri = (new Uri($environment->parse($step->targetSpecification->server)))->withPath($path);
        $request = $request->withUri($uri);

//        dd(new \League\OpenAPIValidation\PSR7\ResponseAddress('/transactions', 'post', 400));
//
//        $validator = (new \League\OpenAPIValidation\PSR7\ValidatorBuilder)->fromYaml($step->targetSpecification->schema)->getServerRequestValidator();
//
//        try {
//            $match = $validator->validate($request);
//        } catch (ValidationFailed $e) {
//            $response = new Response(400, $request->getHeaders(), $e->getMessage());
//        }
//
//        $validator = (new \League\OpenAPIValidation\PSR7\ValidatorBuilder)->fromYaml($step->targetSpecification->schema)->getResponseValidator();
//
//        try {
//            $operation = new \League\OpenAPIValidation\PSR7\OperationAddress('/transactions', 'post') ;
//            $match = $validator->validate($operation, $response);
//        } catch (ValidationFailed $e) {
//            dd($e);
//        }
//
//        dd($response, $response->getStatusCode(), $response->getBody()->getContents());

        $response = (new Client(['http_errors' => false]))->send($request);

        $suite = new TestSuite();
        $suite->addTest(new ValidateRequestTest($request));
        $suite->addTest(new ValidateResponseTest($response));

        $runner = new TestRunner();
        $result = $runner->run($suite);

        dd($result);
    }
}
