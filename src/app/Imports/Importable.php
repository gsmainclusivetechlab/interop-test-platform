<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;

interface Importable
{
    /**
     * @param array $row
     * @return Model
     */
    public function import(array $row): Model;
}
