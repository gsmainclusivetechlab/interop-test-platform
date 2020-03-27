<?php declare(strict_types=1);

namespace App\Testing\Constraints;

use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Constraint;

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
     * @var Validator
     */
    protected $validator;

    /**
     * @param array $rules
     * @param array $messages
     */
    public function __construct(array $rules, array $messages = [], array $attributes = [])
    {
        $this->rules = $rules;
        $this->messages = $messages;
        $this->attributes = $attributes;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function matches($data): bool
    {
        $this->validator = Validator::make($data, $this->rules, $this->messages, $this->attributes);

        return $this->validator->passes();
    }

    /**
     * @param  string $data
     * @return string
     */
    public function failureDescription($data): string
    {
        return $this->toString();
    }

    /**
     * @param mixed $other
     * @return string
     */
    protected function additionalFailureDescription($other): string
    {
        return implode(PHP_EOL, $this->validator->errors()->all());
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'validation passes';
    }
}
