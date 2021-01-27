<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Middleware\SetContentLengthHeaders;
use App\Http\Middleware\SetJsonHeaders;

class Controller extends \App\Http\Controllers\Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware([
            SetJsonHeaders::class,
            SetContentLengthHeaders::class,
        ]);
    }
}
