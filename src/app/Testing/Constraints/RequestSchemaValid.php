<?php declare(strict_types=1);

namespace App\Testing\Constraints;

use cebe\openapi\spec\OpenApi;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;

class RequestSchemaValid extends Constraint
{
    /**
     * @var OpenApi
     */
    protected $scheme;

    /**
     * @param OpenApi $scheme
     */
    public function __construct(OpenApi $scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function matches($data): bool
    {
        $validator = (new ValidatorBuilder)->fromSchema($this->scheme)->getRequestValidator();

        try {
            $validator->validate($data);
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
        return 'request schema valid';
    }
}
