<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Http\Client\Response;
use App\Testing\TestCase;
use League\OpenAPIValidation\PSR7\{
    CallbackResponseAddress,
    ResponseAddress,
    SpecFinder,
};
use League\OpenAPIValidation\PSR7\Exception\NoPath;
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
     * @var string
     */
    protected $apiSpec;

    /**
     * @var ResponseAddress
     */
    protected $operationAddress;

    /**
     * @var SpecFinder
     */
    protected $specFinder;

    /**
     * @param Response $response
     * @param string $apiSpec
     * @param ResponseAddress|CallbackResponseAddress $operationAddress
     * @param SpecFinder $specFinder
     */
    public function __construct(
        Response $response,
        string $apiSpec,
        $operationAddress,
        SpecFinder $specFinder
    ) {
        $this->response = $response;
        $this->apiSpec = $apiSpec;
        $this->operationAddress = $operationAddress;
        $this->specFinder = $specFinder;
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
            'name' => $this->apiSpec,
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
