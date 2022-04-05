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
            'session_available' => $this->session_available,
            'defaultSession' => $this->defaultSession,
            'users' => GroupUserResource::collection(
                $this->whenLoaded('users')
            ),
            'sessions' => GroupUserResource::collection(
                $this->whenLoaded('sessions')
            ),
            'can' => [
                'update' => auth()
                    ->user()
                    ->can('update', $this->resource),
                'delete' => auth()
                    ->user()
                    ->can('delete', $this->resource),
                'admin' => auth()
                    ->user()
                    ->can('admin', $this->resource),
            ],
        ];
    }
}
