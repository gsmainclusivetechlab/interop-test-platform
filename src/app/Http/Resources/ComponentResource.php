<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComponentResource extends JsonResource
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
            'simulated' => $this->simulated,
            'apiService' => (new ApiServiceResource($this->whenLoaded('apiService'))),
            'connections' => static::collection($this->whenLoaded('connections')),
        ];
    }
}
