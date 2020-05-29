<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestRunResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'session' => new SessionResource($this->whenLoaded('session')),
            'testCase' => new TestCaseResource($this->whenLoaded('testCase')),
            'testResults' => TestResultResource::collection(
                $this->whenLoaded('testResults')
            ),
            'total' => $this->total,
            'passed' => $this->passed,
            'failures' => $this->failures,
            'successful' => $this->successful,
            'duration' => $this->duration,
            'created_at' => $this->created_at->diffForHumans(),
            'completed_at' => $this->completed_at
                ? $this->completed_at->diffForHumans()
                : '',
        ];
    }
}
