<?php declare(strict_types=1);

namespace App\Services\Yaml;

use Illuminate\Database\Eloquent\Model;

interface Importable
{
    /**
     * @param array $row
     * @return Model
     */
    public function toModel(array $row): Model;

    /**
     * @param array $row
     * @param Model $model
     */
    public function beforeImport(array $row, Model $model);

    /**
     * @param array $row
     * @param Model $model
     */
    public function afterImport(array $row, Model $model);
}
