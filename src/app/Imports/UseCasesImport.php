<?php

namespace App\Imports;

use App\Models\Scenario;
use App\Models\UseCase;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UseCasesImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * @var Scenario
     */
    protected $scenario;

    /**
     * UseCasesImport constructor.
     * @param Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        $this->scenario = $scenario;
    }

    /**
     * @param array $row
     * @return UseCase|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Model[]|null
     */
    public function model(array $row)
    {
        dd($row);
        return new UseCase([
            //
        ]);
    }
}
