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
            'paths' => static::collection($this->whenLoaded('paths')),
            'apiService' => (new ApiServiceResource($this->whenLoaded('apiService'))),
        ];
    }
}
