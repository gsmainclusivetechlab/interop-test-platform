<?php

namespace Tests;

use Illuminate\Support\Facades\Validator;

trait InteractsWithValidation
{
    /**
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return $this
     */
    protected function assertValidationPasses(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $this->assertTrue(Validator::make($data, $rules, $messages, $customAttributes)->passes());

        return $this;
    }

    /**
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return $this
     */
    protected function assertValidationFails(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $this->assertTrue(Validator::make($data, $rules, $messages, $customAttributes)->fails());

        return $this;
    }
}
