<?php

namespace App\Http\Resources\Settings;

use Collective\Html\HtmlFacade;
use Illuminate\Http\Resources\Json\JsonResource;

class TestSessionResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Throwable
     */
    public function toArray($request)
    {
        return [
            'name' => HtmlFacade::link('#', $this->name)->toHtml(),
            'use_cases_count' => 0,
            'test_cases_count' => 0,
            'status' => view('sessions.includes.status', ['session' => $this])->render(),
        ];
    }
}
