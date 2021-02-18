<?php declare(strict_types=1);

namespace App\Testing\Tests;

class RepeatResponseScriptValidationTest extends ResponseScriptValidationTest
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return __('Intermediate Response :response_count: :name', [
            'response_count' => $this->testResult->iteration,
            'name' => $this->testScript->name,
        ]);
    }
}
