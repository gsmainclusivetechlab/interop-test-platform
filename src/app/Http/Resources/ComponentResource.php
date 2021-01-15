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
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'base_url' => $this->whenPivotLoaded(
                'session_components',
                function () {
                    return $this->pivot->base_url;
                },
                $this->base_url
            ),
            'use_encryption' => $this->whenPivotLoaded(
                'session_components',
                function () {
                    return $this->pivot->use_encryption;
                }
            ),
            'position' => $this->position,
            'sutable' => $this->sutable,
            'certificate_id' => $this->whenPivotLoaded(
                'session_components',
                function () {
                    return $this->pivot->certificate_id;
                }
            ),
            'connections' => static::collection(
                $this->whenLoaded('connections')
            ),
            'can' => [
                'update' => auth()
                    ->user()
                    ->can('update', $this->resource),
                'delete' => auth()
                    ->user()
                    ->can('delete', $this->resource),
            ],
        ];
    }
}
