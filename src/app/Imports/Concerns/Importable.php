<?php

namespace App\Imports\Concerns;

use Illuminate\Database\Eloquent\Model;

trait Importable
{
    /**
     * @param array $rows
     * @throws \Throwable
     */
    public function import(array $rows)
    {
        collect($rows)->each(function ($row) {
            $this->doImport($row)->saveOrFail();
        });
    }

    /**
     * @param array $row
     * @return Model
     */
    abstract protected function doImport(array $row): Model;
}
