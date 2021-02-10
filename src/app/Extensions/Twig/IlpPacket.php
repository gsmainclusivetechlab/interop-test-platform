<?php

namespace App\Extensions\Twig;

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

    public static function validateIlpPacket(
        string $ilpPacket,
        array $parameters,
        array $requestData
    ): bool {
        $transactionObject = static::decodeIlpPacket($ilpPacket);

        $amountKey = 'body.' . Arr::get($parameters, 0, 'amount.amount');

        if (
            Arr::get($requestData, $amountKey) !=
            Arr::get($transactionObject, 'packet.amount')
        ) {
            throw new Exception(__('Amounts not equal'));
        }

        return true;
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
