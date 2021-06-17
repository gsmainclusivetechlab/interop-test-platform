<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\SimulatorPlugin;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SimulatorPlugin
 */
class SimulatorPluginResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'name' => $this->name,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
