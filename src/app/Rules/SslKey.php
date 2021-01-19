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
        return $value &&
            openssl_pkey_get_private(
                file_get_contents($value->getRealPath()),
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
}
