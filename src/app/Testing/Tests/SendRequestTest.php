<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Testing\TestCase;

final class SendRequestTest extends TestCase
{
    public function testA()
    {
        $this->assertTrue(false);
        return 333;
    }

    /**
     * @depends testA
     */
    public function testB($arg)
    {
        dd($arg);
    }
}
