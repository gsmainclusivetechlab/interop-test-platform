<?php

namespace App\Http\Requests;

use App\Models\Component;
use App\Rules\SslCertificate;
use App\Rules\SslKey;
use Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class SessionRequest extends FormRequest
{
    public function rules(): array
    {
        $urlRules = $this->getComponents()
            ->mapWithKeys(function (Component $component) {
                $id = $component->id;
                $useEncription = $this->boolean(
                    "components.{$id}.use_encryption"
                );
                $filesRequired = Rule::requiredIf(
                    $useEncription &&
                        !$this->input("components.{$id}.certificate_id") &&
                        !$component->pivot->implicitSut
                );

                return [
                    "components.{$id}.base_url" => [
                        'required',
                        'url',
                        'max:255',
                    ],
                    "components.{$id}.use_encryption" => ['boolean'],

                    "components.{$id}.certificate_id" => [
                        Rule::requiredIf(
                            $useEncription &&
                                !$this->hasFile("certificates.{$id}.ca_crt") &&
                                !$component->pivot->implicitSut
                        ),
                        'nullable',
                        'exists:certificates,id',
                    ],
                    "certificates.{$id}.ca_crt" => [
                        $filesRequired,
                        'nullable',
                        new SslCertificate(),
                    ],
                    "certificates.{$id}.client_crt" => [
                        $filesRequired,
                        'nullable',
                        new SslCertificate(),
                    ],
                    "certificates.{$id}.client_key" => [
                        $filesRequired,
                        'nullable',
                        new SslKey(
                            $this->input("certificates.{$id}.passphrase")
                        ),
                    ],
                    "certificates.{$id}.passphrase" => ['nullable', 'string'],
                ];
            })
            ->all();

        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'group_environment_id' => [
                'nullable',
                'exists:group_environments,id',
            ],
            'environments' => ['nullable', 'array'],
            'fileEnvironments' => ['nullable', 'array'],
            'test_cases' => ['required', 'array', 'exists:test_cases,id'],
        ] + $urlRules;
    }

    public function attributes(): array
    {
        return $this->getComponents()
            ->mapWithKeys(function (Component $component) {
                $id = $component->id;
                return [
                    "components.{$id}.base_url" => "{$component->name} URL",

                    "components.{$id}.certificate_id" => __('Certificate'),
                    "certificates.{$id}.ca_crt" => __('CA certificate'),
                    "certificates.{$id}.client_crt" => __('Client certificate'),
                    "certificates.{$id}.client_key" => __('Client key'),
                    "certificates.{$id}.passphrase" => __('Pass phrase'),
                ];
            })
            ->all();
    }

    protected function getComponents(): Collection
    {
        return $this->route()->parameter('session')->components;
    }
}
