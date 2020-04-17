<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestCaseResource extends JsonResource
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
            'behavior' => $this->behavior,
            'useCase' => new UseCaseResource($this->whenLoaded('useCase')),
            'testSteps' => TestStepResource::collection($this->whenLoaded('testSteps')),
            'lastTestRun' => new TestRunResource($this->whenLoaded('lastTestRun', function () {
                return $this->whenPivotLoaded('session_test_cases', function () {
                    return $this->lastTestRun()
                        ->where('session_id', $this->pivot->session_id)
                        ->first();
                }, $this->lastTestRun);
            })),
            'can' => [
                'delete' => auth()->user()->can('delete', $this->resource),
            ],
        ];
    }
}
