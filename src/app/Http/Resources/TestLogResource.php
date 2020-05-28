<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestLogResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'request' => $this->request->toArray(),
            'status_code' => $this->status_code,
            'created_at' => $this->created_at->diffForHumans(),
            'session' => new SessionResource($this->whenLoaded('session')),
        ];
    }
}
