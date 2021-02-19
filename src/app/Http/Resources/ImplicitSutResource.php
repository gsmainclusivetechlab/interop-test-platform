<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImplicitSutResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'version' => $this->version,
            'url' => $this->url,
            'use_encryption' => $this->use_encryption,
            'has_sessions' => $this->hasSessions(),
            'certificate' => CertificateResource::make(
                $this->whenLoaded('certificate')
            ),
        ];
    }
}
