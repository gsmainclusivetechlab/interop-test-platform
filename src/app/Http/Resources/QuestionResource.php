<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'question' => $this->question,
            'preconditions' => $this->preconditions ?? [],
            'type' => $this->type,
            'values' => collect($this->values)->map(function ($value, $key) {
                return [
                    'id' => $key,
                    'label' => $value
                ];
            })->values()->toArray()
        ];
    }
}
