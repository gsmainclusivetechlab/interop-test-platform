<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ComponentResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //        dd($this->whenPivotLoaded(
        //            'test_case_components',
        //            function () {
        //                return $this->pivot->component_versions;
        //            },
        //            $this->whenLoaded(
        //                'testCases',
        //                function () {
        //                    return $this->testCases
        //                        ->pluck('pivot.component_versions')
        //                        ->filter()
        //                        ->values()
        //                        ->all();
        //                },
        //                []
        //            )
        //        ));
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'base_url' => $this->whenPivotLoaded(
                'session_components',
                function () {
                    return $this->pivot->base_url;
                }
            ),
            'use_encryption' => $this->whenPivotLoaded(
                'session_components',
                function () {
                    return $this->pivot->use_encryption;
                }
            ),
            'certificate_id' => $this->whenPivotLoaded(
                'session_components',
                function () {
                    return $this->pivot->certificate_id;
                }
            ),
            'versions' => $this->whenPivotLoaded(
                'test_case_components',
                function () {
                    return $this->pivot->component_versions;
                },
                $this->whenLoaded(
                    'testCases',
                    function () {
                        return $this->testCases
                            ->pluck('pivot.component_versions')
                            ->filter()
                            ->flatten()
                            ->unique()
                            ->values();
                    },
                    []
                )
            ),
            'connections' => static::collection(
                $this->whenLoaded('connections')
            ),
        ];
    }
}
