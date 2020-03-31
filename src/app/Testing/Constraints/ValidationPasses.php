<?php declare(strict_types=1);

namespace App\Testing\Constraints;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;

class ValidationPasses extends Constraint
{
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param array $rules
     * @param array $messages
     * @param array $attributes
     */
    public function __construct(array $rules, array $messages = [], array $attributes = [])
    {
        $this->rules = $rules;
        $this->messages = $messages;
        $this->attributes = $attributes;
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function matches($data): bool
    {
        $validator = Validator::make($data, $this->rules, $this->messages, $this->attributes);

        try {
            $validator->validate();
            return true;
        } catch (ValidationException $e) {
            throw new ExpectationFailedException(implode(PHP_EOL, $validator->errors()->all()));
        }
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'validation passes';
    }
}
