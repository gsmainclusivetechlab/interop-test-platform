<?php declare(strict_types=1);

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestRunResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'session_id' => $this->session_id,
            'testCase' => $this->test_case_id,
            'total' => $this->total,
            'passed' => $this->passed,
            'failures' => $this->failures,
            'duration' => $this->duration,
            'created_at' => $this->created_at->toDateTimeString(),
            'completed_at' => optional($this->completed_at)->toDateTimeString(),
            'completed' => (bool)$this->completed_at,
            'successful' => $this->completed_at ? $this->successful : null,
        ];
    }
}
