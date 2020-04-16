<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UseCaseResource extends JsonResource
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
            'testCases' => TestCaseResource::collection($this->whenLoaded('testCases')),
            'test_cases_count' => $this->test_cases_count,
        ];
    }
}
