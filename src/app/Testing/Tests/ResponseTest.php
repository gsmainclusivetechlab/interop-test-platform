<?php

namespace App\Testing\Tests;

use App\Testing\TestCase;
use Illuminate\Http\Response;

final class ResponseTest extends TestCase
{
    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        parent::__construct('doRun', [$response]);
    }

    /**
     * @param Response $response
     * @return void
     */
    public function doRun(Response $response)
    {
        $this->assertEmpty(null);
    }
}
