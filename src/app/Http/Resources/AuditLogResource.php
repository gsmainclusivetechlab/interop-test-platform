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
            'fullname' => new UserResource($this->whenLoaded('fullname')),
            'action' => $this->action,
            'subject' => $this->subject,
            'type' => $this->type,
            'meta' => $this->meta,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
