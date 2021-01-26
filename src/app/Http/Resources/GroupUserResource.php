<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupUserResource extends JsonResource
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
            'admin' => $this->pivot->admin,
            'can' => [
                'update' => auth()
                    ->user()
                    ->can('update', $this->pivot),
                'delete' => auth()
                    ->user()
                    ->can('delete', $this->pivot),
                'toggleAdmin' => auth()
                    ->user()
                    ->can('toggleAdmin', $this->pivot),
            ],
        ];
    }
}
