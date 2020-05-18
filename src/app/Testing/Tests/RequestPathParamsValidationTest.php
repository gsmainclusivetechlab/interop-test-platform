<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Http\Client\Request;
use App\Models\Specification;
use App\Testing\TestCase;
use League\OpenAPIValidation\PSR7\Exception\Validation\InvalidPath;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\SpecFinder;
use League\OpenAPIValidation\PSR7\Validators\PathValidator;
use PHPUnit\Framework\AssertionFailedError;
use Throwable;

class RequestPathParamsValidationTest extends TestCase
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Specification
     */
    protected $apiScheme;

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
     * @param Specification $apiScheme
     * @param OperationAddress $operationAddress
     * @param array $specs
     */
    public function __construct(Request $request, Specification $apiScheme, OperationAddress $operationAddress, array $specs)
    {
        $this->request = $request;
        $this->apiScheme = $apiScheme;
        $this->operationAddress = $operationAddress;
        $this->specs = $specs;
    }

    /**
     * @return void
     */
    public function test()
    {
        $validator = new PathValidator(new SpecFinder($this->apiScheme->openapi));

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
        return __('Request: :name API Scheme path params are specified correctly', ['name' => $this->apiScheme->name]);
    }

    /**
     * @return array
     * @throws InvalidPath
     */
    public function getActual(): array
    {
        return $this->operationAddress->parseParams($this->request->toPsrRequest()->getUri()->getPath());
    }

    /**
     * @return array
     */
    public function getExpected(): array
    {
        return tap([], function (&$specs) {
            foreach ($this->specs as $name => $spec) {
                $specs[$name] = $spec->getSerializableData();
            }
        });
    }
}
