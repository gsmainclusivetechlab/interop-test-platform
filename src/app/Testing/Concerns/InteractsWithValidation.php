<?php

namespace App\Testing\Concerns;

use App\Testing\Constraints\ValidationPasses;
use PHPUnit\Framework\Constraint\LogicalNot as ReverseConstraint;

trait InteractsWithValidation
{
    /**
     * @param array $data
     * @param array $rules
     * @return $this
     */
    protected function assertValidationPassed(array $data, array $rules)
    {
        $this->assertThat($data, new ValidationPasses($rules));

        return $this;
    }

    /**
     * @param array $data
     * @param array $rules
     * @return $this
     */
    protected function assertValidationFailed(array $data, array $rules)
    {
        $this->assertThat($data, new ReverseConstraint(new ValidationPasses($rules)));

        return $this;
    }
}
