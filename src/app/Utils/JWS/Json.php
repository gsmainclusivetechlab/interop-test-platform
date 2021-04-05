<?php

namespace App\Utils\JWS;

class Json extends \Gamegos\JWS\Util\Json
{
    public static function encode($data)
    {
        $json = @json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(self::transformJsonError());
        }

        return $json;
    }
}
