<?php

namespace App\Imports;

use App\Imports\Concerns\Importable;
use App\Models\TestCase;
use App\Models\TestStep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class TestStepsImport
{
    use Importable;

    /**
     * @var TestCase
     */
    protected $testCase;

    /**
     * TestStepsImport constructor.
     * @param TestCase $testCase
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * @param array $row
     * @return Model
     */
    protected function toModel(array $row): Model
    {
        $model = $this->testCase->testSteps()->make(Arr::only($row, TestStep::make()->getFillable()));
        $model->setAttribute('source_id', $this->testCase->useCase->scenario->components()->where('name', Arr::get($row, 'source'))->value('id'));
        $model->setAttribute('target_id', $this->testCase->useCase->scenario->components()->where('name', Arr::get($row, 'target'))->value('id'));
        $model->created(function ($model) use ($row) {
//            (new TestRequestScriptsImport($model))->importMany(Arr::get($row, 'test_request_scripts', []));
        });

        return $model;
    }
}
