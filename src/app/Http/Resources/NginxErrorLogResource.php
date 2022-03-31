<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NginxErrorLogResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'level' => $this->level,
            'date' => $this->date->format('d M Y, H:i:s'),
            'request' => $this->attributes['header'],
            'message' => $this->attributes['body'],
        ];
    }
}
