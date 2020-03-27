<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Testing\Concerns\InteractsWithValidation;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

class SomeTest extends TestCase
{
    use InteractsWithValidation;

    /**
     * @return void
     */
    public function test()
    {
        $validator = Validator::make(['data' => '', 'body' => ''], ['data' => 'required']);
        dd($validator->passes());
//        throw new AssertionFailedError('ttt');
//        $this->assertValidationPassed(['data' => '', 'body' => ''], ['data' => 'required']);
    }
}
