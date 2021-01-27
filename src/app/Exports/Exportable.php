<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Model;

interface Exportable
{
    /**
     * @param Model $testCase
     * @return string
     */
    public function export(Model $testCase): string;
}
