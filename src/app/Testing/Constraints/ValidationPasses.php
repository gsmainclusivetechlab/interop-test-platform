<?php declare(strict_types=1);

namespace App\Testing\Constraints;

use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Constraint;

class ValidationPasses extends Constraint
{
    /**
     * @var array
     */
    protected $rules;
    /**
     * @var array
     */
    protected $messages;
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param array $rules
     * @param array $messages
     */
    public function __construct(array $rules, array $messages = [])
    {
        $this->rules = $rules;
        $this->messages = $messages;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function matches($data): bool
    {
        $validator = Validator::make($data, $this->rules, $this->messages);
        $passes = $validator->passes();

        if (!$passes) {
            $this->errors = $validator->errors()->all();
        }

        return $passes;
    }

    /**
     * @param  string $data
     * @return string
     */
    public function failureDescription($data): string
    {
        return $this->toString() . ' (' . implode(PHP_EOL, $this->errors) . ')';
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'validation passes';
    }
}
