<?php

namespace App\Imports;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        Validator::validate(
            $rows,
            [
                '*' => ['array'],
                '*.title' => ['string'],
                '*.items' => ['array', 'required'],
                '*.items.*.title' => ['string', 'required'],
                '*.items.*.text' => ['string', 'required'],
            ]
        );
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
