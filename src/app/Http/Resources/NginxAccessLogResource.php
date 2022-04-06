<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NginxAccessLogResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('d M Y, H:i:s'),
            'ip_address' => $this->attributes['context']['ip_address'],
            'request' => $this->attributes['context']['request'],
            'response_code' => $this->attributes['context']['response_code'],
            'bytes' => $this->attributes['context']['bytes'],
            'url' => $this->attributes['context']['url'],
            'user_agent' => $this->attributes['context']['user_agent'],
        ];
    }
}
