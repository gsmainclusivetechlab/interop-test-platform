<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupMemberResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'company' => $this->company,
            'role' => $this->role,
            'can' => [
                'delete' => auth()
                    ->user()
                    ->can('delete', $this->pivot),
            ],
        ];
    }
}
