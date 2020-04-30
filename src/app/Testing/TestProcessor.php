<?php declare(strict_types=1);

namespace App\Testing;

use League\Pipeline\ProcessorInterface;
use Throwable;

class TestProcessor implements ProcessorInterface
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param mixed $payload
     * @param callable ...$stages
     * @return mixed
     */
    public function process($payload, callable ...$stages)
    {
        foreach ($stages as $stage) {
            try {
                $stage($payload);
            } catch (Throwable $e) {
                $this->errors[] = $e->getMessage();
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return empty($this->errors);
    }
}
