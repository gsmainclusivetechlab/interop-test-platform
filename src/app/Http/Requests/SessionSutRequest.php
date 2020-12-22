<?php

namespace App\Http\Requests;

use App\Models\Session;
use Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class SessionSutRequest extends FormRequest
{
    public function rules(): array
    {
        $isCompliance = Session::isCompliance(session('session.type'));

        return $this->components()
            ->mapWithKeys(function ($component, $id) {
                $useEncription = (int) Arr::get($component, 'use_encryption');

                return [
                    "components.{$id}.certificate_id" => [
                        Rule::requiredIf($useEncription && !$this->hasFile("components.{$id}.certificate")),
                        'nullable',
                        Rule::exists('certificates', 'id')
                            ->whereIn('group_id', auth()->user()->groups->pluck('id')->all())
                    ],
                    "components.{$id}.certificate" => [
                        Rule::requiredIf($useEncription && !Arr::get($component, 'certificate_id')),
                        'nullable',
                        'mimetypes:text/plain',
                    ],
                ];
            })
            ->merge([
                'components' => [
                    $isCompliance ? 'required' : 'nullable',
                    'array',
                    'min:' . (int) $isCompliance,
                ],
                'components.*.id' => [
                    'required',
                    Rule::exists('components', 'id')->where('sutable', true),
                ],
                'components.*.base_url' => ['required', 'url', 'max:255'],
                'components.*.use_encryption' => ['boolean'],
            ])
            ->all();
    }

    public function attributes(): array
    {
        return $this->components()->mapWithKeys(function ($component, $id) {
            return [
                "components.{$id}.certificate_id" => __('Certificate'),
                "components.{$id}.certificate" => __('Certificate'),
            ];
        })->merge([
            'components' => __('SUTs'),
            'components.*.base_url' => __('URL'),
        ])->all();
    }

    protected function components(): Collection
    {
        return collect($this->get('components', []));
    }
}
