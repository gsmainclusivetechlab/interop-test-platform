<?php

namespace App\Extensions\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class Base64 extends AbstractExtension
{
    public function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64url_decode($data)
    {
        return base64_decode(
            strtr($data, '-_', '+/') .
                str_repeat('=', 3 - ((3 + strlen($data)) % 4))
        );
    }

    public function getFilters()
    {
        return [
            new TwigFilter('base64_urlencode', [$this, 'base64url_encode']),
            new TwigFilter('base64_urldecode', [$this, 'base64url_decode']),
        ];
    }
}
