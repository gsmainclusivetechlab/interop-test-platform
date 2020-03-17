<?php

namespace App\Imports;

use App\Models\TestCase;
use App\Models\UseCase;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

class TestCasesImport implements ToModel
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
     * @return TestCase|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Model[]|null
     */
    public function model(array $row)
    {
        return new TestCase([
            //
        ]);
    }
}
