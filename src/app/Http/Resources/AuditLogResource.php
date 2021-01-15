<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\AuditTypeEnum;
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
            'user' => new UserResource($this->whenLoaded('user')),
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
        switch (AuditTypeEnum::fromValue($type)) {
            case AuditTypeEnum::NO_TYPE():
                break;
            case AuditTypeEnum::SESSION_TYPE():
                return 'sessions.show';
            case AuditTypeEnum::GROUP_TYPE():
                return 'groups.show';
        }
        return $subject;
    }
}
