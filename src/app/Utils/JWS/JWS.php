<?php

namespace App\Utils\JWS;

use Gamegos\JWS\Algorithm\AlgorithmInterface;
use Gamegos\JWS\Exception\UnspecifiedAlgorithmException;
use Gamegos\JWS\Exception\UnsupportedAlgorithmException;
use Gamegos\JWS\Util\Base64Url;

class JWS extends \Gamegos\JWS\JWS
{
    /**
     * @param $name
     * @return AlgorithmInterface
     */
    private function _getAlgorithm($name)
    {
        if (!isset($this->algorithms[$name])) {
            throw new UnsupportedAlgorithmException(
                sprintf("Signing algorithm '%s' is not supported", $name)
            );
        }

        return $this->algorithms[$name];
    }

    /**
     * @param  array  $headers
     * @param  mixed  $payload
     * @param         $key
     * @return string
     */
    public function encode(array $headers, $payload, $key)
    {
        if (empty($headers['alg'])) {
            throw new UnspecifiedAlgorithmException(
                "'alg' header parameter is required."
            );
        }

        $algorithm = $this->_getAlgorithm($headers['alg']);

        $headerComponent = Base64Url::encode(Json::encode($headers));
        $payloadComponent = Base64Url::encode(Json::encode($payload));

        $dataToSign = $headerComponent . '.' . $payloadComponent;
        $signature = Base64Url::encode($algorithm->sign($key, $dataToSign));

        return $dataToSign . '.' . $signature;
    }
}
