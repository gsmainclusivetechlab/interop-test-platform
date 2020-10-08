<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestStepResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'method' => $this->method,
            'request' => optional($this->request)->toArray(),
            'response' => optional($this->response)->toArray(),
            'position' => $this->position,
            'source' => new ComponentResource($this->whenLoaded('source')),
            'target' => new ComponentResource($this->whenLoaded('target')),
            'apiSpec' => new ApiSpecResource($this->whenLoaded('apiSpec')),
            'testSetups' => TestSetupResource::collection(
                $this->whenLoaded('testSetups')
            ),
            'testScripts' => TestScriptResource::collection(
                $this->whenLoaded('testScripts')
            ),
        ];
    }
}
