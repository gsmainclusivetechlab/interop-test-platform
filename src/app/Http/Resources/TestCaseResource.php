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
            'use_case' => new UseCaseResource($this->whenLoaded('useCase')),
            'test_steps_count' => $this->test_steps_count,
            'can' => [
                'delete' => auth()->user()->can('delete', $this->resource),
            ],
        ];
    }
}
