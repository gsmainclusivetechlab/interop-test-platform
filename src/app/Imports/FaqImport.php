<?php

namespace App\Imports;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Yaml\Yaml;

class FaqImport
{
    /**
     * @param Request $request
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function import(Request $request)
    {
        $rows = Yaml::parse($request->file('file')->get());
        $faqValidator = Validator::make(
            $rows,
            [
                '*' => ['array'],
                '*.title' => ['string'],
                '*.items' => ['array', 'required'],
                '*.items.*.title' => ['string', 'required'],
                '*.items.*.text' => ['string', 'required'],
            ],
            [
                '*.array' => 'The data must be an array.',
                '*.title.string' => 'The title must be an string.',
                '*.items.array' => 'The items must be an array.',
                '*.items.required' => 'The items field is required.',
                '*.items.*.title.string' => 'The item title must be an string.',
                '*.items.*.title.required' => 'The item title field is required.',
                '*.items.*.text.string' => 'THe item text must be an string.',
                '*.items.*.text.required' => 'The item text field is required.',
            ]
        );

        if ($faqValidator->fails()) {
            $errors = [];
            foreach ($faqValidator->errors()->all() as $message) {
                if (!in_array($message, $errors)) {
                    $errors[] = $message;
                }
            }
            throw ValidationException::withMessages($errors);
        }
        $faq = Faq::first();
        if (!$faq) {
            Faq::create([
                'description' => $request->input('description'),
                'content' => $rows,
                'active' => false,
            ]);
        } else {
            $faq->update([
                'description' => $request->input('description'),
                'content' => $rows,
                'active' => false,
            ]);
        }
    }
}
