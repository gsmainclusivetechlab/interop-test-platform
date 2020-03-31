<?php declare(strict_types=1);

namespace App\Testing\Constraints;

use cebe\openapi\spec\OpenApi;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use Psr\Http\Message\RequestInterface;

class ResponseSchemaValid extends Constraint
{
    /**
     * @var OpenApi
     */
    protected $scheme;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param OpenApi $scheme
     * @param RequestInterface $request
     */
    public function __construct(OpenApi $scheme, RequestInterface $request)
    {
        $this->scheme = $scheme;
        $this->request = $request;
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function matches($data): bool
    {
        $requestValidator = (new ValidatorBuilder)->fromSchema($this->scheme)->getRequestValidator();
        $responseValidator = (new ValidatorBuilder)->fromSchema($this->scheme)->getResponseValidator();

        try {
            $operationAddress = $requestValidator->validate($this->request);
            $responseValidator->validate($operationAddress, $data);
            return true;
        } catch (ValidationFailed $e) {
            throw new ExpectationFailedException($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'response schema valid';
    }
}
