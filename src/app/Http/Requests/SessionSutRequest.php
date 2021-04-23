<?php

namespace App\Http\Requests;

use App\Http\Controllers\Sessions\Register\Traits\Queries;
use App\Models\Session;
use App\Rules\SslCertificate;
use Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class SessionSutRequest extends FormRequest
{
    use Queries;

    public function rules(): array
    {
        $isCompliance = Session::isCompliance(session('session.type'));

        $components = $this->getComponents();
        $versions = $this->getVersions($components);

        return $this->components()
            ->mapWithKeys(function ($component, $id) use ($versions) {
                $useEncription = (int) Arr::get($component, 'use_encryption');
                $filesRequired = Rule::requiredIf(
                    $useEncription && !Arr::get($component, 'certificate_id')
                );

                return [
                    "components.{$id}.certificate_id" => [
                        Rule::requiredIf(
                            $useEncription &&
                                !$this->hasFile("components.{$id}.ca_crt")
                        ),
                        'nullable',
                        'exists:certificates,id',
                    ],
                    "components.{$id}.ca_crt" => [
                        $filesRequired,
                        'nullable',
                        new SslCertificate(),
                    ],
                    "components.{$id}.client_crt" => [
                        'nullable',
                        new SslCertificate(),
                    ],
                    "components.{$id}.version" => [
                        Rule::requiredIf(is_array($versions->get($id))),
                        'nullable',
                        'string',
                    ],
                    "components.{$id}.implicit_sut_id" => [
                        'nullable',
                        'exists:implicit_suts,id',
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
                    Rule::exists('components', 'id'),
                ],
                'components.*.base_url' => ['required', 'url', 'max:255'],
                'components.*.use_encryption' => ['nullable', 'boolean'],
            ])
            ->all();
    }

    public function attributes(): array
    {
        return $this->components()
            ->mapWithKeys(function ($component, $id) {
                return [
                    "components.{$id}.certificate_id" => __('Certificate'),
                    "components.{$id}.ca_crt" => __('CA certificate'),
                    "components.{$id}.client_crt" => __('Client certificate'),
                    "components.{$id}.version" => __('Version'),
                ];
            })
            ->merge([
                'components' => __('SUTs'),
                'components.*.base_url' => __('URL'),
            ])
            ->all();
    }

    protected function components(): Collection
    {
        return collect($this->get('components', []));
    }
}
