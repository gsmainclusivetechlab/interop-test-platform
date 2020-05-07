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
            'name' => $this->name,
            'request' => $this->request ? $this->request->toArray() : null,
            'response' => $this->response ? $this->response->toArray() : null,
            'position' => $this->position,
            'source' => new ComponentResource($this->whenLoaded('source')),
            'target' => new ComponentResource($this->whenLoaded('target')),
//            'apiScheme' => new ApiSchemeResource($this->whenLoaded('apiScheme')),
            'testSetups' => TestSetupResource::collection($this->whenLoaded('testSetups')),
            'testScripts' => TestScriptResource::collection($this->whenLoaded('testScripts')),
        ];
    }
}
