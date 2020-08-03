<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Mail\Markdown;

class TestCaseResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'public' => $this->public,
            'behavior' => $this->behavior,
            'description' => Markdown::parse($this->description)->toHtml(),
            'precondition' => Markdown::parse($this->precondition)->toHtml(),
            'owner' => new UserResource($this->whenLoaded('owner')),
            'useCase' => new UseCaseResource($this->whenLoaded('useCase')),
            'testSteps' => TestStepResource::collection(
                $this->whenLoaded('testSteps')
            ),
            'lastTestRun' => new TestRunResource(
                $this->whenLoaded('lastTestRun', function () {
                    return $this->whenPivotLoaded(
                        'session_test_cases',
                        function () {
                            return $this->lastTestRun()
                                ->where('session_id', $this->pivot->session_id)
                                ->first();
                        },
                        $this->lastTestRun
                    );
                })
            ),
            'can' => [
                'update' => auth()
                    ->user()
                    ->can('update', $this->resource),
                'delete' => auth()
                    ->user()
                    ->can('delete', $this->resource),
                'togglePublic' => auth()
                    ->user()
                    ->can('togglePublic', $this->resource),
            ],
        ];
    }
}
