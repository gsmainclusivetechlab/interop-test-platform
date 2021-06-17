<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class Extension implements Rule
{
    protected array $extensions;

    /** @var string */
    protected $message;

    public function __construct(array $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (
            !$value instanceof UploadedFile ||
            !in_array($value->clientExtension(), $this->extensions)
        ) {
            $this->message = __(
                'The :attribute must be a file with extension: :extensions.',
                [
                    'attribute' => $attribute,
                    'extensions' => implode(', ', $this->extensions),
                ]
            );

            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
