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
            'role_name' => $this->role_name,
            'email_verified_at' => $this->email_verified_at->toDateTimeString(),
            'trashed' => $this->trashed(),
            'can' => [
                'delete' => auth()->user()->can('delete', $this->resource),
                'restore' => auth()->user()->can('restore', $this->resource),
                'promoteAdmin' => auth()->user()->can('promoteAdmin', $this->resource),
                'relegateAdmin' => auth()->user()->can('relegateAdmin', $this->resource),
            ],
        ];
    }
}
