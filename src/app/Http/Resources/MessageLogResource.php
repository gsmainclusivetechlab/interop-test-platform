<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageLogResource extends JsonResource
{
    /**
     * @param  \App\Models\MessageLog  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'request' => $this->request->toArray(),
            'exception' => $this->exception,
            'created_at' => $this->created_at->diffForHumans(),
            'session' => new SessionResource($this->whenLoaded('session')),
            'test_case' => new TestCaseResource($this->whenLoaded('testCase')),
            'test_step' => new TestStepResource($this->whenLoaded('testStep')),
            'test_run' => new TestRunResource($this->whenLoaded('testRun')),
        ];
    }
}
