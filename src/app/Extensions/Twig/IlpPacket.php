<?php

namespace App\Extensions\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IlpPacket extends AbstractExtension
{
    /**
     * Get functions.
     *
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('ilpFulfilment', [$this, 'ilpFulfilment']),
            new TwigFunction('ilpCondition', [$this, 'ilpCondition']),
            new TwigFunction('ilpPacket', [$this, 'ilpPacket']),
        ];
    }

    function byteArrayToString($x)
    {
        return join(array_map('chr', $x));
    }

    function dateToInterledgerTime($date)
    {
        $packed =
            $date->format('YmdHis') .
            str_pad($date->format('v'), 3, '0', STR_PAD_LEFT);
        return $this->byteArrayToString(unpack('C*', $packed));
    }

    function lengthPrefix($length)
    {
        if ($length <= 127) {
            // For buffers shorter than 128 bytes, we simply prefix the length as a
            // single byte.
            return pack('C', $length);
        } else {
            // For buffers longer than 128 bytes, we should write a single byte
            // containing the length of the length in bytes, with the most significant
            // bit set, and then write the length of the buffer itself. For now our simulators shouldn't need this path.
            // TODO: See https://github.com/interledgerjs/interledgerjs/blob/d6cce52c48fec6bdf95401ee3593360d461aaa93/packages/oer-utils/src/lib/writer.ts#L271-L278
            throw new Exception('Long data not yet implemented');
        }
    }

    // variable-length data needs to be prefixed with its length for decoding
    function varLength($x)
    {
        $bytes = unpack('C*', $x);
        $length = $this->lengthPrefix(count($bytes));
        return $length . $this->byteArrayToString($bytes);
    }

    function ilpFulfilment()
    {
        // TODO: read a seed from an environment value or something
        return hex2bin(
            'd8011d77fbfed64323c157b8755f11b75f18024dd5b6510039ad003b03a75a95'
        );
    }

    function ilpCondition($fulfillment)
    {
        return hash('sha256', $fulfillment, true);
    }

    function ilpPacket($amount, $expires, $condition, $destination, $data)
    {
        $amountB = pack('J', $amount);
        $expiresB = $this->dateToInterledgerTime(new \DateTime($expires));
        $destinationB = $this->varLength($destination);
        $dataB = $this->varLength($data);
        $contentB = $this->varLength(
            $amountB . $expiresB . $condition . $destinationB . $dataB
        );

        // magic number 12 corresponds to ILP "Prepare" type packet
        $typeB = pack('C', 12);
        $envelopeB = $typeB . $contentB;
        return $envelopeB;
    }
}
