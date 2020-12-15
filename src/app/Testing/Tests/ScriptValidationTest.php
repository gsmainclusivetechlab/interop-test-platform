<?php


namespace App\Testing\Tests;


use App\Utils\TokenSubstitution;
use App\Utils\TwigSubstitution;

trait ScriptValidationTest
{
    /**
     * @return array
     */
    public function getExpected(): array
    {
        $rules = (array) $this->testScript->rules;
        $tokenSubstitution = new TokenSubstitution($this->testResult->session->environments());
        $twigSubstitution = new TwigSubstitution($this->testResult->testRun->testResults);
        array_walk_recursive(
            $rules,
            function (&$value) use ($tokenSubstitution, $twigSubstitution) {
                if (is_string($value)) {
                    $value = $tokenSubstitution->replace($value);
                    $value = $twigSubstitution->replace($value);
                }
            }
        );

        return $rules;
    }
}
