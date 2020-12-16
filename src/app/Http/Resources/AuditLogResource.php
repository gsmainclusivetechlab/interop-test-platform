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
            'fullname' => new UserResource($this->whenLoaded('fullname')),
            'action' => $this->action,
            'subject' => $this->subject,
            'type' => $this->type,
            'url' => $this->getSubject($this->subject, $this->type),
            'meta' => $this->meta,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }

    /**
     * @param $subject
     * @param $type
     * @return string
     */
    private function getSubject($subject, $type)
    {
        switch ($type) {
            case 0:
                break;
            case 1:
                return 'sessions.show';
            case 2:
                return 'groups.show';
        }
        return $subject;
    }
}
