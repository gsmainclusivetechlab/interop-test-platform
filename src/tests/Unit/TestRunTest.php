<?php

namespace Tests\Unit;

use App\Models\TestRun;
use Tests\TestCase;

class TestRunTest extends TestCase
{
    /**
     * Test Run store.
     *
     * @return void
     */
    public function testTestRunStore()
    {
        $testRun = factory(TestRun::class)->make();
        $this->assertTrue($testRun->save());
    }

    /**
     * Test Run delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestRunDelete()
    {
        $testRun = factory(TestRun::class)->create();
        $testRun->delete();
        $this->assertDeleted($testRun);
    }
}
