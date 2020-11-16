<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    /**
     * @param  \App\Models\AuditLog  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'actor' => $this->actor('actor'),
        ];
    }
}
