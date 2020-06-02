<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email_verified_at' => $this->email_verified_at
                ? $this->email_verified_at->toDateTimeString()
                : null,
            'trashed' => $this->trashed(),
            'can' => [
                'verify' => auth()
                    ->user()
                    ->can('verify', $this->resource),
                'delete' => auth()
                    ->user()
                    ->can('delete', $this->resource),
                'restore' => auth()
                    ->user()
                    ->can('restore', $this->resource),
                'promoteRole' => auth()
                    ->user()
                    ->can('promoteRole', $this->resource),
            ],
        ];
    }
}
