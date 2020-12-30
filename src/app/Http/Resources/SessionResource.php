<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class SessionResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Collection $testCases */
        $testCases = $this->whenLoaded(
            'testCases',
            function () {
                return $this->testCases;
            },
            function () {
                return $this->testCases()
                    ->with([
                        'lastTestRun' => function ($query) {
                            $query->where('session_id', $this->id);
                        },
                    ])
                    ->get();
            }
        );

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'type' => $this->type,
            'typeName' => $this->type_name,
            'status' => $this->status,
            'reason' => $this->reason,
            'statusName' => $this->status_name,
            'completable' => $this->completable,
            'description' => $this->description,
            'use_encryption' => $this->use_encryption,
            'environments' => $this->environments,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'testCasesCount' => $testCases->count(),
            'useCasesCount' => $testCases
                ->pluck('use_case_id')
                ->unique()
                ->count(),
            'progress' => [
                'passed' => $testCases
                    ->map(function ($testCase) {
                        return $testCase->lastTestRun &&
                            $testCase->lastTestRun->successful
                            ? 1
                            : 0;
                    })
                    ->sum(),
                'failures' => $testCases
                    ->map(function ($testCase) {
                        return $testCase->lastTestRun &&
                            !$testCase->lastTestRun->successful
                            ? 1
                            : 0;
                    })
                    ->sum(),
            ],
            'groupEnvironment' => new GroupEnvironmentResource(
                $this->whenLoaded('groupEnvironment')
            ),
            'components' => ComponentResource::collection(
                $this->whenLoaded('components')
            ),
            'testCases' => TestCaseResource::collection(
                $this->whenLoaded('testCases')
            ),
            'lastTestRun' => new TestRunResource(
                $this->whenLoaded('lastTestRun')
            ),
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
