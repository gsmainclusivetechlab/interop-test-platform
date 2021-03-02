<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Http\Client\Request;
use App\Testing\TestCase;
use League\OpenAPIValidation\PSR7\{
    CallbackAddress,
    OperationAddress,
    SpecFinder
};
use League\OpenAPIValidation\PSR7\Exception\NoPath;
use League\OpenAPIValidation\PSR7\Validators\BodyValidator\BodyValidator;
use League\OpenAPIValidation\PSR7\Validators\CookiesValidator\CookiesValidator;
use League\OpenAPIValidation\PSR7\Validators\{
    HeadersValidator,
    PathValidator,
    QueryArgumentsValidator,
    SecurityValidator,
    ValidatorChain
};
use PHPUnit\Framework\AssertionFailedError;
use Throwable;

class RequestSchemeValidationTest extends TestCase
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $apiSpec;

    /**
     * @var OperationAddress
     */
    protected $operationAddress;

    /**
     * @var SpecFinder
     */
    protected $specFinder;

    /**
     * @var bool
     */
    protected $isCallback;

    /**
     * @param Request $request
     * @param string $apiSpec
     * @param OperationAddress|CallbackAddress $operationAddress
     * @param SpecFinder $specFinder
     * @param bool $isCallback
     */
    public function __construct(
        Request $request,
        string $apiSpec,
        $operationAddress,
        SpecFinder $specFinder,
        bool $isCallback
    ) {
        $this->request = $request;
        $this->apiSpec = $apiSpec;
        $this->operationAddress = $operationAddress;
        $this->specFinder = $specFinder;
        $this->isCallback = $isCallback;
    }

    /**
     * @return void
     */
    public function test()
    {
        $validators = [
            new HeadersValidator($this->specFinder),
            new CookiesValidator($this->specFinder),
            new BodyValidator($this->specFinder),
            new QueryArgumentsValidator($this->specFinder),
            new SecurityValidator($this->specFinder),
        ];
        if (!$this->isCallback) {
            $validators[] = new PathValidator($this->specFinder);
        }
        $validator = new ValidatorChain(...$validators);

        try {
            $validator->validate(
                $this->operationAddress,
                $this->request->toPsrRequest()
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
        return __('Request: :name API Spec validation', [
            'name' => $this->apiSpec,
        ]);
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
