<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComponentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $components = $this->testCase->components;

        if ($this->isMethod('PUT')) {
            $components = $components->where(
                'slug',
                '!=',
                $this->component->slug
            );
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::notIn($components->pluck('slug')),
            ],
            'versions' => ['nullable', 'array'],
        ];
    }
}
