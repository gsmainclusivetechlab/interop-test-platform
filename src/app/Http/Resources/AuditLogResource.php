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
            'actor' => $this->actor,
            'action' => $this->action,
            'subject' => $this->subject,
            'meta' => $this->meta,
        ];
    }
}
