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
            'description' => $this->description,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'suts' => ComponentResource::collection($this->whenLoaded('suts')),
            'testCases' => TestCaseResource::collection($this->whenLoaded('testCases')),
            'lastTestRun' => new TestRunResource($this->whenLoaded('lastTestRun')),
            'can' => [
                'delete' => auth()->user()->can('delete', $this->resource),
            ],
        ];
    }
}
