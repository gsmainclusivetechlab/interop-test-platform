<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestRequestScript;
use App\Testing\Constraints\ValidationPasses;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\SelfDescribing;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use Psr\Http\Message\ServerRequestInterface;
use SebastianBergmann\Timer\Timer;

class ValidateRequestTest extends Assert implements Test, SelfDescribing
{
    protected $testScript;

    public function __construct(ServerRequestInterface $request, TestRequestScript $testScript)
    {
        $this->testScript = $testScript;
    }

    /**
     * @return int
     */
    public function count()
    {
        return 1;
    }

    /**
     * @param TestResult|null $result
     * @return TestResult
     */
    public function run(TestResult $result = null): TestResult
    {
        $result = $result ?: new TestResult();
        $result->startTest($this);
        Timer::start();
        $this->assertThat([], new ValidationPasses([]));
        $result->endTest($this, Timer::stop());
        return $result;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'test';
        return $this->testScript->name;
    }
}
