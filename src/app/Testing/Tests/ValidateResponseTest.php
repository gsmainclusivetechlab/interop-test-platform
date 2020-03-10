<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestRequestScript;
use App\Testing\Constraints\ValidationPasses;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class ValidateResponseTest extends TestCase
{
    protected $testScript;

    public function __construct(ServerRequestInterface $request, TestRequestScript $testScript)
    {
        $this->testScript = $testScript;
        parent::__construct('test');
    }

    /**
     * @return void
     */
    public function test()
    {
        $this->assertThat(['code' => 201], new ValidationPasses(['code' => 'in:200']));
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
