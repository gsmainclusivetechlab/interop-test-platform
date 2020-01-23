<?php

namespace App\Http\DataTables;

use App\Models\TestSession;
use League\Fractal\TransformerAbstract;

class TestSessionTransformer extends TransformerAbstract
{
    /**
     * @param TestSession $model
     * @return array
     */
    public function transform(TestSession $model)
    {
        return [
            'name' => $model->name,
        ];
    }
}
