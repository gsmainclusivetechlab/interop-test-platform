<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Client\Request;
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
            'pattern' => $this->pattern,
            'trigger' => $this->trigger,
            'request' => optional(
                optional($this->request)->toArray(),
                function ($request) {
                    $request['uri'] = rawurldecode($request['uri']);

                    return $request;
                }
            ),
            'response' => optional($this->response)->toArray(),
            'repeat' => [
                'max' => $this->repeat_max,
                'count' => $this->repeat_count,
                'condition' => $this->repeat_condition,
                'response' => optional($this->repeat_response)->toArray(),
            ],
            'position' => $this->position,
            'mtls' => $this->mtls,
            'source' => new ComponentResource($this->whenLoaded('source')),
            'target' => new ComponentResource($this->whenLoaded('target')),
            'apiSpec' => new ApiSpecResource($this->whenLoaded('apiSpec')),
            'testSetups' => TestSetupResource::collection(
                $this->whenLoaded('testSetups')
            ),
            'testScripts' => TestScriptResource::collection(
                $this->whenLoaded('testScripts')
            ),
            'callback' => [
                'method' => $this->callback_origin_method,
                'path' => $this->callback_origin_path,
                'name' => $this->callback_name,
            ],
        ];
    }
}
