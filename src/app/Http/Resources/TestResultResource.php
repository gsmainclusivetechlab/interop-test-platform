<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestResultResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'testRun' => new TestResultResource($this->whenLoaded('testRun')),
            'testStep' => new TestStepResource($this->whenLoaded('testStep')),
            'testExecutions' => TestExecutionResource::collection($this->whenLoaded('testExecutions')),
            'successful' => $this->successful,
            'request' => $this->request,
            'response' => $this->response,
        ];
    }
}
