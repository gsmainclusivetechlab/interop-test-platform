<?php declare(strict_types=1);

namespace App\Testing;

use Illuminate\Pipeline\Pipeline;
use Throwable;

class TestPipeline extends Pipeline
{
    /**
     * @var int
     */
    protected $passed = 0;

    /**
     * @var int
     */
    protected $errors = 0;

    /**
     * @param mixed $carry
     * @return mixed
     */
    protected function handleCarry($carry)
    {
        $this->passed++;

        return parent::handleCarry($carry);
    }

    /**
     * @param mixed $passable
     * @param Throwable $e
     */
    protected function handleException($passable, Throwable $e)
    {
        $this->errors++;

        return parent::handleException($passable, $e);
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return !$this->errors;
    }
}
