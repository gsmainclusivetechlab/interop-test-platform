<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Testing\RequestTestCase;

class ValidateRequestTest extends RequestTestCase
{
    public function test()
    {
        $this->assertFalse(false);

        return 333;
    }

//    public function getName(bool $withDataSet = true): string
//    {
//        if ($withDataSet) {
//            return (string)  microtime(true) . $this->getDataSetAsString(false);
//        }
//
//        return (string) microtime(true);
//    }
}
