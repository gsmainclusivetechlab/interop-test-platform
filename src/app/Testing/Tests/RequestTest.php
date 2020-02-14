<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Testing\TestCase;
use Illuminate\Http\Request;

final class RequestTest extends TestCase
{
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct('doRun', [$request]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function doRun(Request $request)
    {
        $this->assertValidationNotPasses([], ['title' => 'required|unique:posts|max:255']);
    }
}
