<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware([
            'auth',
            'verified',
            'can:administer',
        ]);
    }
}
