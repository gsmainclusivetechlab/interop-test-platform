<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestStep;
use App\Testing\Tests\ValidateRequestTest;
use App\Testing\Tests\ValidateResponseTest;
use PHPUnit\Framework\TestSuite;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TestSuiteLoader
{
    /**
     * @var TestStep
     */
    protected $step;

    /**
     * @param TestStep $step
     */
    public function __construct(TestStep $step)
    {
        $this->step = $step;
    }

    /**
     * @param ServerRequestInterface $request
     * @return TestSuite
     */
    public function loadRequestTests(ServerRequestInterface $request)
    {
        $suite = new TestSuite();
        $scripts = $this->step->testRequestScripts;

        foreach ($scripts as $script) {
            $suite->addTest(new ValidateRequestTest($script, $request));
        }

        return $suite;
    }

    /**
     * @param ResponseInterface $response
     * @return TestSuite
     */
    public function loadResponseTests(ResponseInterface $response)
    {
        $suite = new TestSuite();
        $scripts = $this->step->testResponseScripts;

        foreach ($scripts as $script) {
            $suite->addTest(new ValidateResponseTest($script, $response));
        }

        return $suite;
    }
}
