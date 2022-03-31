<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppLogResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'level' => $this->level,
            'date' => $this->date->format('d M Y, H:i:s'),
            'context' => $this->context,
            'stack_traces' => $this->stack_traces,
        ];
    }
}
