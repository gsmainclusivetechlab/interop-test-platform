<?php declare(strict_types=1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CertificateResource
 *
 * @package App\Http\Resources
 *
 * @property Carbon $created_at
 */
class CertificateResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'certificable_id' => $this->certificable_id,
            'name' => $this->name,
            'passphrase' => $this->passphrase,
            'created_at' => $this->created_at->toDateTimeString(),
            'can' => [
                'delete' => auth()
                    ->user()
                    ->can('admin', $this->resource->group),
            ],
        ];
    }
}
