<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Http\Client\Response;
use App\Models\ApiSpec;
use App\Testing\TestCase;
use League\OpenAPIValidation\PSR7\Exception\NoPath;
use League\OpenAPIValidation\PSR7\ResponseAddress;
use League\OpenAPIValidation\PSR7\SpecFinder;
use League\OpenAPIValidation\PSR7\Validators\BodyValidator\BodyValidator;
use League\OpenAPIValidation\PSR7\Validators\HeadersValidator;
use League\OpenAPIValidation\PSR7\Validators\ValidatorChain;
use PHPUnit\Framework\AssertionFailedError;
use Throwable;

class ResponseSchemeValidationTest extends TestCase
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ApiSpec
     */
    protected $apiSpec;

    /**
     * @var ResponseAddress
     */
    protected $operationAddress;

    /**
     * @var ApiSpec
     */
    protected $specFinder;

    /**
     * @param Response $response
     * @param ApiSpec $apiSpec
     * @param ResponseAddress $operationAddress
     */
    public function __construct(
        Response $response,
        ApiSpec $apiSpec,
        ResponseAddress $operationAddress
    ) {
        $this->response = $response;
        $this->apiSpec = $apiSpec;
        $this->operationAddress = $operationAddress;
        $this->specFinder = new SpecFinder($apiSpec->openapi);
    }

    /**
     * @return void
     */
    public function test()
    {
        $validator = new ValidatorChain(
            new HeadersValidator($this->specFinder),
            new BodyValidator($this->specFinder)
        );

        try {
            $validator->validate(
                $this->operationAddress,
                $this->response->toPsrResponse()
            );
        } catch (Throwable $e) {
            throw new AssertionFailedError($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return __('Response: :name API Spec validation', [
            'name' => $this->apiSpec->name,
        ]);
    }

    /**
     * @return array
     */
    public function getActual(): array
    {
        return $this->response->toArray();
    }

    /**
     * @return array
     */
    public function getExpected(): array
    {
        try {
            return (array) $this->specFinder
                ->findResponseSpec($this->operationAddress)
                ->getSerializableData();
        } catch (NoPath $e) {
            return [];
        }
    }
}
