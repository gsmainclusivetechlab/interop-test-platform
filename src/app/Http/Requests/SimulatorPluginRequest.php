<?php

namespace App\Http\Requests;

use App\Rules\Extension;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SimulatorPluginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'file' => [
                Rule::requiredIf($this->isMethod('POST')),
                'nullable',
                'file',
                new Extension(['js']),
            ],
        ];
    }
}
