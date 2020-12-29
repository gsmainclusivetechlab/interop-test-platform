<?php

namespace App\Http\Requests;

use App\Models\Session;
use App\Rules\SslCertificate;
use App\Rules\SslKey;
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
                        'mimetypes:text/plain',
                        new SslCertificate(),
                    ],
                    "components.{$id}.client_crt" => [
                        $filesRequired,
                        'nullable',
                        'mimetypes:text/plain',
                        new SslCertificate(),
                    ],
                    "components.{$id}.client_key" => [
                        $filesRequired,
                        'nullable',
                        'mimetypes:text/plain',
                        new SslKey(
                            Arr::get(
                                $this->all(),
                                "components.{$id}.passphrase"
                            )
                        ),
                    ],
                    "components.{$id}.passphrase" => ['nullable', 'string'],
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
        return $this->components()
            ->mapWithKeys(function ($component, $id) {
                return [
                    "components.{$id}.certificate_id" => __('Certificate'),
                    "components.{$id}.ca_crt" => __('CA certificate'),
                    "components.{$id}.client_crt" => __('Client certificate'),
                    "components.{$id}.client_key" => __('Client key'),
                    "components.{$id}.passphrase" => __('Pass phrase'),
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
