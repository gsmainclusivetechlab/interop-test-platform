<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'active' => $this->active,
            'updated_at' => $this->updated_at->toDateTimeString(),
            'can' => [
                'toggleActive' => auth()
                    ->user()
                    ->can('toggleActive', $this->resource),
            ],
        ];
    }
}
