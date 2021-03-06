<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UseCaseResource extends JsonResource
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
            'description' => $this->description,
            'testCases' => TestCaseResource::collection(
                $this->whenLoaded('testCases')
            ),
            'testCasesCount' => $this->whenLoaded('testCases', function () {
                return $this->testCases->unique('test_case_group_id')->count();
            }),
            'can' => [
                'update' => auth()
                    ->user()
                    ->can('update', $this->resource),
                'delete' => auth()
                    ->user()
                    ->can('delete', $this->resource),
            ],
        ];
    }
}
