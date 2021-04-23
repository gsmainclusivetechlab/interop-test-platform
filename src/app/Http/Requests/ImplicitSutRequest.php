<?php

namespace App\Http\Requests;

use App\Models\ImplicitSut;
use App\Rules\SslCertificate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImplicitSutRequest extends FormRequest
{
    public function rules(): array
    {
        $isRequiredCertificates = Rule::requiredIf(function () {
            if (!$this->boolean('use_encryption')) {
                return false;
            }

            return $this->isMethod('POST') ||
                !$this->route()->parameter('implicit_sut')->certificate;
        });

        return [
            'slug' => ['required', 'string', 'max:255'],
            'version' => [
                'required',
                'string',
                'max:255',
                Rule::unique(ImplicitSut::class)
                    ->ignore($this->route()->parameter('implicit_sut'))
                    ->where('slug', $this->get('slug')),
            ],
            'url' => ['required', 'url'],
            'use_encryption' => ['required', 'boolean'],
            'ca_crt' => [
                $isRequiredCertificates,
                'nullable',
                new SslCertificate(),
            ],
            'client_crt' => [
                $isRequiredCertificates,
                'nullable',
                new SslCertificate(),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'ca_crt' => __('CA certificate'),
            'client_crt' => __('Client certificate'),
        ];
    }
}
