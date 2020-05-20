<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Http\Client\Request;
use App\Models\ApiSpec;
use App\Testing\TestCase;
use League\OpenAPIValidation\PSR7\Exception\NoPath;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\SpecFinder;
use League\OpenAPIValidation\PSR7\Validators\BodyValidator\BodyValidator;
use League\OpenAPIValidation\PSR7\Validators\CookiesValidator\CookiesValidator;
use League\OpenAPIValidation\PSR7\Validators\HeadersValidator;
use League\OpenAPIValidation\PSR7\Validators\PathValidator;
use League\OpenAPIValidation\PSR7\Validators\QueryArgumentsValidator;
use League\OpenAPIValidation\PSR7\Validators\SecurityValidator;
use League\OpenAPIValidation\PSR7\Validators\ValidatorChain;
use PHPUnit\Framework\AssertionFailedError;
use Throwable;

class RequestSchemeValidationTest extends TestCase
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ApiSpec
     */
    protected $apiSpec;

    /**
     * @var OperationAddress
     */
    protected $operationAddress;

    /**
     * @var ApiSpec
     */
    protected $specFinder;

    /**
     * @param Request $request
     * @param ApiSpec $apiSpec
     * @param OperationAddress $operationAddress
     */
    public function __construct(Request $request, ApiSpec $apiSpec, OperationAddress $operationAddress)
    {
        $this->request = $request;
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
            new CookiesValidator($this->specFinder),
            new BodyValidator($this->specFinder),
            new QueryArgumentsValidator($this->specFinder),
            new PathValidator($this->specFinder),
            new SecurityValidator($this->specFinder)
        );

        try {
            $validator->validate($this->operationAddress, $this->request->toPsrRequest());
        } catch (Throwable $e) {
            throw new AssertionFailedError($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return __('Request: :name API Spec validation', ['name' => $this->apiSpec->name]);
    }

    /**
     * @return array
     */
    public function getActual(): array
    {
        return $this->request->toArray();
    }

    /**
     * @return array
     */
    public function getExpected(): array
    {
        try {
            return (array) $this->specFinder
                ->findOperationSpec($this->operationAddress)
                ->getSerializableData();
        } catch (NoPath $e) {
            return [];
        }
    }
}
