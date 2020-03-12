<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestStep;
use App\Testing\Tests\ValidateRequestTest;
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
     * @param ResponseInterface $response
     * @return TestSuite
     */
    public function load(ServerRequestInterface $request, ResponseInterface $response)
    {
        $suite = new TestSuite();
        $requestSuite = new RequestTestSuite(ValidateRequestTest::class);
        $requestSuite->setRequest($request);
        $suite->addTestSuite($requestSuite);
        $requestSuite = new RequestTestSuite(ValidateRequestTest::class);
        $requestSuite->setRequest($request);
        $suite->addTestSuite($requestSuite);

        return $suite;
    }
}
