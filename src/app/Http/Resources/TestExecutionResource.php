<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestExecutionResource extends JsonResource
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
            'actual' => $this->actual,
            'expected' => $this->expected,
            'exception' => $this->exception,
            'successful' => $this->successful,
        ];
    }
}
