<?php

namespace App\Testing;

use App\Testing\Constraints\ValidationPasses;
use PHPUnit\Framework\Constraint\LogicalNot as ReverseConstraint;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return $this
     */
    public function assertValidationPasses(array $data, array $rules, array $messages = [])
    {
        $this->assertThat($data, new ValidationPasses($rules, $messages));

        return $this;
    }

    /**
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return $this
     */
    public function assertValidationNotPasses(array $data, array $rules, array $messages = [])
    {
        $this->assertThat($data, new ReverseConstraint(new ValidationPasses($rules, $messages)));

        return $this;
    }
}
