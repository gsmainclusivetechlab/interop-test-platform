<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScenarioResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'components_count' => $this->components_count,
            'use_cases_count' => $this->use_cases_count,
            'test_cases_count' => $this->test_cases_count,
        ];
    }
}
