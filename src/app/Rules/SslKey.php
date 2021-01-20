<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Nyholm\Psr7\UploadedFile;

class SslKey implements Rule
{
    /** @var string|null */
    protected $passphrase;

    public function __construct(?string $passphrase)
    {
        $this->passphrase = $passphrase;
    }

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

        return openssl_pkey_get_private($fileData, $this->passphrase) ||
            openssl_pkey_get_private(
                $this->getFromBinnary($fileData),
                $this->passphrase
            );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The private key or passphrase is not valid.';
    }

    protected function getFromBinnary(string $fileData): string
    {
        return "-----BEGIN PRIVATE KEY-----\n" .
            chunk_split(base64_encode($fileData), 64, "\n") .
            '-----END PRIVATE KEY-----';
    }
}
