<?php

namespace App\Extensions\Twig;

use Exception;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IlpPacket extends AbstractExtension
{
    const TYPE_ILP_PAYMENT = 1;
    const TYPE_ILP_PREPARE = 12;
    const TYPE_ILP_FULFILL = 13;
    const TYPE_ILP_REJECT = 14;

    /**
     * Get functions.
     *
     * @return TwigFunction[]
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
        $typeB = pack('C', static::TYPE_ILP_PREPARE);

        return $typeB . $contentB;
    }

    public function validateIlpPacket($ilpPacket)
    {
        //        dd(
        //            rtrim(strtr(base64_encode(
        //                $this->ilpPacket(199, "+1 day", $this->ilpCondition($this->ilpFulfilment()),"g.gh.msisdn.447584248916", "")
        //            ), '+/', '-_'), '=')
        //        );
        dd(static::getTransactionObject($ilpPacket));
        return false;
    }

    protected function getTransactionObject($inputIlpPacket)
    {
        $jsonPacket = static::decodeIlpPacket($inputIlpPacket);
    }

    protected function decodeIlpPacket($inputIlpPacket)
    {
        $binaryPacket = Base64::base64url_decode($inputIlpPacket);

        $jsonPacket = static::deserializeIlpPayment($binaryPacket);
    }

    protected function deserializeIlpPacket($binaryPacket)
    {
        $type = unpack('C', $binaryPacket[0]);

        switch ($type) {
            case static::TYPE_ILP_PAYMENT:
                $packet = static::deserializeIlpPayment($binaryPacket);
                $typeString = 'ilp_payment';
        }
    }

    protected static function deserializeIlpPayment($binaryPacket)
    {
        $contents = static::readString(substr($binaryPacket, 1));

        return [];
    }

    protected static function readString($string)
    {
        $length = unpack('C', $string[0])[1];

        return substr($string, 1, $length);
    }
}
