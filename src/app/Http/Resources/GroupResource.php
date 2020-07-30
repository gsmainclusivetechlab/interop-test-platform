<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'domain' => $this->domain,
            'description' => $this->description,
            'members' => GroupMemberResource::collection(
                $this->whenLoaded('members')
            ),
            'can' => [
                'update' => auth()
                    ->user()
                    ->can('update', $this->resource),
                'delete' => auth()
                    ->user()
                    ->can('delete', $this->resource),
                'invite' => auth()
                    ->user()
                    ->can('invite', $this->resource),
            ],
        ];
    }
}
