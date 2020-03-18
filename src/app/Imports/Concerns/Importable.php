<?php

namespace App\Imports\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait Importable
{
    /**
     * @param array $row
     * @throws \Throwable
     */
    public function import(array $row)
    {
        $this->toModel($row)->saveOrFail();
    }

    /**
     * @param array $rows
     * @throws \Throwable
     */
    public function importMany(array $rows)
    {
        DB::transaction(function () use ($rows) {
            collect($rows)->each(function ($row) {
                $this->import($row);
            });
        });
    }

    /**
     * @param array $row
     * @return Model
     */
    abstract protected function toModel(array $row): Model;
}
