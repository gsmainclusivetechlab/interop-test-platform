<?php

namespace App\Imports;

use App\Imports\Concerns\Importable;
use App\Models\TestRequestScript;
use App\Models\TestStep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class TestRequestScriptsImport
{
    use Importable;

    /**
     * @var TestStep
     */
    protected $testStep;

    /**
     * TestRequestScriptsImport constructor.
     * @param TestStep $testStep
     */
    public function __construct(TestStep $testStep)
    {
        $this->testStep = $testStep;
    }

    /**
     * @param array $row
     * @return Model
     */
    public function doImport(array $row): Model
    {
        $model = $this->testStep->testRequestScripts()
            ->make(Arr::only($row, TestRequestScript::make()->getFillable()))
            ->setAttribute('test_step_id', $this->testStep->id);

        return $model;
    }
}
