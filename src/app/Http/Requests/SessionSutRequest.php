<?php

namespace App\Http\Requests;

use App\Models\Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class SessionSutRequest extends FormRequest
{
    public function rules(): array
    {
        $isCompliance = Session::isCompliance(session('session.type'));

        return $this->components()->mapWithKeys(function ($id) {
            return ["base_urls.{$id}" => ['required', 'url', 'max:255']];
        })->merge([
            'component_ids' => [$isCompliance ? 'required' : 'nullable', 'array', 'min:' . (int)$isCompliance],
            'component_ids.*' => ['required', Rule::exists('components', 'id')
                ->where('sutable', true)
            ],
        ])->all();
    }

    public function attributes(): array
    {
        return $this->components()->mapWithKeys(function ($id) {
            return ["base_urls.{$id}" => __('URL')];
        })->merge([
            'component_ids' => __('SUTs'),
        ])->all();
    }

    protected function components(): Collection
    {
        return collect($this->get('component_ids', []));
    }
}
