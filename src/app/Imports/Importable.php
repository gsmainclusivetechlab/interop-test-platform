<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;

interface Importable
{
    /**
     * @param array $rows
     * @return Model
     */
    public function import(array $rows): Model;
}
