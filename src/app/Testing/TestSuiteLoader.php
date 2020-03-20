<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use App\Testing\Tests\ValidateRequestTest;
use App\Testing\Tests\ValidateResponseTest;
use PHPUnit\Framework\TestSuite;

class TestSuiteLoader
{

    /**
     * @param TestResult $result
     * @return TestSuite
     */
    public function load(TestResult $result)
    {
        $suite = new TestSuite();
        $requestScripts = $result->testStep->testRequestScripts;

        foreach ($requestScripts as $requestScript) {
            $suite->addTest(new ValidateRequestTest($requestScript, $result->request));
        }

        $responseScripts = $result->testStep->testResponseScripts;

        foreach ($responseScripts as $responseScript) {
            $suite->addTest(new ValidateResponseTest($responseScript, $result->response));
        }

        return $suite;
    }
}
