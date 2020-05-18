<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Http\Client\Request;
use App\Models\ApiSpec;
use App\Testing\TestCase;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\SpecFinder;
use League\OpenAPIValidation\PSR7\Validators\HeadersValidator;
use PHPUnit\Framework\AssertionFailedError;
use Throwable;

class RequestHeaderParamsValidationTest extends TestCase
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
     * @var array
     */
    protected $specs = [];

    /**
     * @param Request $request
     * @param ApiSpec $apiSpec
     * @param OperationAddress $operationAddress
     * @param array $specs
     */
    public function __construct(Request $request, ApiSpec $apiSpec, OperationAddress $operationAddress, array $specs)
    {
        $this->request = $request;
        $this->apiSpec = $apiSpec;
        $this->operationAddress = $operationAddress;
        $this->specs = $specs;
    }

    /**
     * @return void
     */
    public function test()
    {
        $validator = new HeadersValidator(new SpecFinder($this->apiSpec->openapi));

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
        return __('Request: :name API Scheme header params are specified correctly', ['name' => $this->apiSpec->name]);
    }

    /**
     * @return array
     */
    public function getActual(): array
    {
        return tap([], function (&$headers) {
            foreach (array_keys($this->getExpected()) as $name) {
                if ($header = $this->request->toPsrRequest()->getHeaderLine($name)) {
                    $headers[$name] = $header;
                }
            }
        });
    }

    /**
     * @return array
     */
    public function getExpected(): array
    {
        return tap([], function (&$expected) {
            foreach ($this->specs as $name => $spec) {
                $expected[$name] = $spec->getSerializableData();
            }
        });
    }
}
