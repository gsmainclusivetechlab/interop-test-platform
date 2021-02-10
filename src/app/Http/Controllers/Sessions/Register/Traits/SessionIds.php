<?php

namespace App\Http\Controllers\Sessions\Register\Traits;

trait SessionIds
{
    protected function getSessionIds(): array
    {
        return collect(session('session.sut'))
            ->pluck('certificate_id')
            ->filter()
            ->all();
    }
}
