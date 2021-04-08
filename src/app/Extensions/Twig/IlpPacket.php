<?php

namespace App\Extensions\Twig;

use App\Utils\Packer;
use App\Utils\StringReader;
use Arr;
use Carbon\Carbon;
use Exception;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IlpPacket extends AbstractExtension
{
    const TYPE_ILP_PREPARE = 12;

    const INTERLEDGER_TIME_LENGTH = 17;

    /**
     * Get functions.
     *
     * @return TwigFunction[]
     */
    public function getFunctions(): array
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
            // bit set, and then write the length of the buffer itself.
            // See https://github.com/interledgerjs/interledgerjs/blob/d6cce52c48fec6bdf95401ee3593360d461aaa93/packages/oer-utils/src/lib/writer.ts#L271-L278

            $lengthOfLength = $this->getLengthOfLength($length);

            return pack('C', 0x80 | $lengthOfLength) . Packer::pack($lengthOfLength, $length);
        }
    }

    protected function getLengthOfLength($value)
    {
        $sizes = Packer::bytesMaxSize();

        foreach ($sizes as $size) {
            if ($value <= $size['max']) {
                return $size['bytes'];
            }
        }
        throw new Exception('Data size greater than max safe byte');
    }

    /**
     * variable-length data needs to be prefixed with its length for decoding
     *
     * @param $x
     * @return string
     */
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

    public static function validateIlpPacketAmount(
        string $ilpPacket,
        array $parameters,
        array $requestData
    ): bool {
        $transactionObject = static::decodeIlpPacket($ilpPacket);

        $amountKey = Arr::get($parameters, 0, 'body.amount.amount');
        $amount = Arr::get($requestData, $amountKey, $amountKey);
        $ilpAmount = Arr::get($transactionObject, 'packet.amount');

        if ($amount != $ilpAmount) {
            static::throwException(
                'Amounts are not equal',
                $amount,
                $ilpAmount
            );
        }

        return true;
    }

    public static function validateIlpPacketDestination(
        string $ilpPacket,
        array $parameters
    ): bool {
        $transactionObject = static::decodeIlpPacket($ilpPacket);

        $destination = Arr::get($parameters, 0);
        $ilpDestination = Arr::get($transactionObject, 'packet.destination');

        if ($destination != $ilpDestination) {
            static::throwException(
                'Destinations are not equal',
                $destination,
                $ilpDestination
            );
        }

        return true;
    }

    public static function validateIlpPacketCondition(
        string $ilpPacket,
        array $parameters,
        array $requestData
    ): bool {
        $transactionObject = static::decodeIlpPacket($ilpPacket);

        $conditionKey = Arr::get($parameters, 0, 'body.condition');
        $condition = Arr::get($requestData, $conditionKey, $conditionKey);
        $ilpCondition = Arr::get($transactionObject, 'packet.condition');

        if (!hash_equals(Base64::base64url_decode($condition), $ilpCondition)) {
            static::throwException(
                'Conditions are not equal',
                $condition,
                Base64::base64url_encode($ilpCondition)
            );
        }

        return true;
    }

    public static function validateIlpPacketExpiration(
        string $ilpPacket,
        array $parameters,
        array $requestData
    ): bool {
        $transactionObject = static::decodeIlpPacket($ilpPacket);

        $expirationKey = Arr::get($parameters, 0, 'body.expiration');
        $expiration = Carbon::create(
            Arr::get($requestData, $expirationKey, $expirationKey)
        );
        $ilpExpiration = Arr::get($transactionObject, 'packet.expiresAt');

        if (!$expiration || !$expiration->eq($ilpExpiration)) {
            static::throwException(
                'Expirations are not equal',
                $expiration->toRfc3339String(true),
                $ilpExpiration->toRfc3339String(true)
            );
        }

        return true;
    }

    protected static function throwException($error, $compared, $ilpValue)
    {
        throw new Exception(
            __("{$error}. Compared value: {$compared}. ILP value: {$ilpValue}")
        );
    }

    protected static function decodeIlpPacket(string $inputIlpPacket): array
    {
        $binaryPacket = Base64::base64url_decode($inputIlpPacket);

        return static::deserializeIlpPacket($binaryPacket);
    }

    protected static function deserializeIlpPacket(string $binaryPacket): array
    {
        $reader = StringReader::from($binaryPacket);
        $type = $reader->readUInt8Number();
        $contents = $reader->readVarOctetString();

        switch ($type) {
            case static::TYPE_ILP_PREPARE:
                $packet = static::deserializeIlpPrepare($contents);
                $typeString = 'ilp_prepare';
                break;
            default:
                throw new Exception(
                    __('Validation supports only ILP Prepare packets')
                );
        }

        return [
            'type' => $type,
            'packet' => $packet,
            'typeString' => $typeString,
        ];
    }

    protected static function deserializeIlpPrepare(string $contents): array
    {
        $reader = StringReader::from($contents);

        return [
            'amount' => $reader->readUInt64(),
            'expiresAt' => static::interledgerTimeToDate(
                $reader->read(self::INTERLEDGER_TIME_LENGTH)
            ),
            'condition' => $reader->read(32),
            'destination' => $reader->readVarOctetString(),
            'data' => $reader->readVarOctetString(),
        ];
    }

    protected static function interledgerTimeToDate(
        string $interledgerTime
    ): Carbon {
        return Carbon::createFromFormat(
            'YmdHis',
            substr($interledgerTime, 0, 14)
        )->addMilliseconds(substr($interledgerTime, 14, 17));
    }
}
