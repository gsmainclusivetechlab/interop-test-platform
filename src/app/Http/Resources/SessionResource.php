<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
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
            'owner' => new UserResource($this->whenLoaded('owner')),
            'suts' => ComponentResource::collection($this->whenLoaded('suts')),
            'lastTestRun' => new TestRunResource($this->whenLoaded('lastTestRun')),
            'use_cases_count' => $this->testCases->unique('use_case_id')->count(),
            'test_cases_count' => $this->testCases->count(),
            'can' => [
                'delete' => auth()->user()->can('delete', $this->resource),
            ],
        ];
    }
}
