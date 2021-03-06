<?php

namespace App\Testing\Tests;

use App\Utils\TwigSubstitution;

trait ScriptValidationTest
{
    /**
     * @return array
     */
    public function getExpected(): array
    {
        $rules = (array) $this->testScript->rules;
        $substitution = new TwigSubstitution(
            $this->testResult->testRun->testResults,
            $this->testResult->session,
            $this->testResult->testStep
        );
        array_walk_recursive($rules, function (&$value) use ($substitution) {
            if (is_string($value)) {
                $value = $substitution->replace($value);
            }
        });

        return $rules;
    }
}
