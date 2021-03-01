<?php

namespace App\Http\Controllers\Sessions\Traits;

use App\Models\Component;
use App\Models\Group;
use App\Models\Session;
use App\Models\TestStep;

trait WithSutUrls
{
    protected function getSutUrls(Session $session): array
    {
        $groups = auth()
            ->user()
            ->groups()
            ->where('default_session_id', $session->id)
            ->get();
        $sourceIds = $session->testSteps->pluck('source_id');
        $targetIds = $session->testSteps->pluck('target_id');
        $sessionComponents = $session->components->whereIn('id', $sourceIds);

        if ($groups->isEmpty()) {
            $urls = Session::getMappedUrls($sessionComponents, $session);

            $items = $this->getComponentsMap(
                $sessionComponents,
                $urls,
                $targetIds
            );
        } else {
            $items = self::mapGroups(
                $groups,
                $sessionComponents,
                $targetIds,
                $session
            );
        }

        return [
            'isGroup' => !$groups->isEmpty(),
            'items' => $items,
        ];
    }

    protected function getConfigUrls(): array
    {
        $testSteps = TestStep::whereIn(
            'test_case_id',
            session('session.info.test_cases')
        )
            ->whereIn('source_id', array_keys(session('session.sut')))
            ->with('source.connections')
            ->get();

        $targetIds = $testSteps->pluck('target_id');
        $sessionComponents = $testSteps->pluck('source')->unique();

        $urls = Session::getMappedUrls($sessionComponents);

        return [
            'groups' => $this->mapGroups(
                auth()
                    ->user()
                    ->groups()
                    ->get(),
                $sessionComponents,
                $targetIds
            ),
            'session' => $this->getComponentsMap(
                $sessionComponents,
                $urls,
                $targetIds
            ),
        ];
    }

    protected function mapGroups(
        $groups,
        $sessionComponents,
        $targetIds,
        $session = null
    ) {
        return $groups->map(function (Group $group) use (
            $session,
            $sessionComponents,
            $targetIds
        ) {
            $urls = Session::getMappedUrls(
                $sessionComponents,
                $session,
                $group
            );

            return [
                $group->id => [
                    'id' => $group->id,
                    'title' => $group->name . ' URLs:',
                    'items' => $this->getComponentsMap(
                        $sessionComponents,
                        $urls,
                        $targetIds
                    ),
                ],
            ];
        });
    }

    protected function getComponentsMap($components, $urls, $targetIds)
    {
        return $components->map(function (Component $component) use (
            $urls,
            $targetIds
        ) {
            return [
                'title' => $component->name,
                'items' => $component->connections
                    ->whereIn('id', $targetIds)
                    ->map(function (Component $connection) use (
                        $component,
                        $urls
                    ) {
                        return [
                            'label' => "$component->name -> $connection->name",
                            'url' => $urls
                                ->get($component->slug)
                                ->get($connection->slug),
                        ];
                    }),
            ];
        });
    }
}
