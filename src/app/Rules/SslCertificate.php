<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class SslCertificate implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  UploadedFile  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (
            !$value ||
            !($fileData = file_get_contents($value->getRealPath()))
        ) {
            return false;
        }

        return openssl_x509_parse($fileData) ||
            openssl_x509_parse($this->getFromBinnary($fileData));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The certificate is not valid.';
    }

    protected function getFromBinnary(string $fileData): string
    {
        return "-----BEGIN CERTIFICATE-----\n" .
            chunk_split(base64_encode($fileData), 64, "\n") .
            '-----END CERTIFICATE-----';
    }
}
