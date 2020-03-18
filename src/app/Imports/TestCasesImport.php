<?php

namespace App\Imports;

use App\Imports\Concerns\Importable;
use App\Models\TestCase;
use App\Models\UseCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class TestCasesImport
{
    use Importable;

    /**
     * @var UseCase
     */
    protected $useCase;

    /**
     * TestCasesImport constructor.
     * @param UseCase $useCase
     */
    public function __construct(UseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    /**
     * @param array $row
     * @return Model
     */
    protected function doImport(array $row): Model
    {
        $model = $this->useCase->testCases()->make(Arr::only($row, TestCase::make()->getFillable()));
        $model->created(function ($model) use ($row) {
            (new TestStepsImport($model))->import(Arr::get($row, 'test_steps', []));
        });
        return $model;
    }
}
