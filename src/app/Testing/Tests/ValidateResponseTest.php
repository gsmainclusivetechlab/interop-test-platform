<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Testing\Constraints\ValidationPasses;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use Psr\Http\Message\ResponseInterface;
use SebastianBergmann\Timer\Timer;

class ValidateResponseTest implements Test
{
    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {

    }

    /**
     * @param TestResult|null $result
     * @return TestResult
     */
    public function run(TestResult $result = null): TestResult
    {
        $result = $result ?: new TestResult;
        Timer::start();
        $result->startTest($this);

        try {
            Assert::assertTrue(false);
            Assert::assertThat([], new ValidationPasses([]));
        } catch (AssertionFailedError $e) {
            $result->addFailure($this, $e, Timer::stop());
        }

        $result->endTest($this, Timer::stop());
        return $result;
    }

    /**
     * @return int
     */
    public function count()
    {
        return 1;
    }
}
