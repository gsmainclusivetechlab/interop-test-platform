<?php

namespace App\Utils;

use Arr;
use Exception;

class Packer
{
    const MAX_BYTE = 4;

    /**
     * @param $length
     * @return string
     * @throws Exception
     */
    public static function format($length)
    {
        switch ($length) {
            case 1:
                return 'C'; // 8 bit
            case 2:
                return 'n'; // 16 bit
            case 3:
                return 'C*'; // 24 bit
            case 4:
                return 'N'; // 32 bit
            default:
                throw new Exception('Data size greater than max safe byte');
        }
    }

    /**
     * @param $bytes
     * @param $data
     * @return false|string
     * @throws Exception
     */
    public static function pack($bytes, $data)
    {
        $format = self::format($bytes);
        switch ($bytes) {
            case 1:
            case 2:
            case 4:
                return pack($format, $data);
            case 3:
                return self::packByBytes($bytes, $data);
            default:
                throw new Exception('Data size greater than max safe byte');
        }
    }

    /**
     * @param $bytes
     * @param $in
     * @return string
     */
    public static function packByBytes($bytes, $in) {
        $pad_to_bits = $bytes * 8;
        $in = decbin($in);
        $in = str_pad($in, $pad_to_bits, '0', STR_PAD_LEFT);
        $out = '';
        for ($i = 0, $len = strlen($in); $i < $len; $i += 8) {
            $out .= chr(bindec(substr($in,$i,8)));
        }

        return $out;
    }

    /**
     * @param $bytes
     * @param $string
     * @return float|int|mixed
     * @throws Exception
     */
    public static function unpack($bytes, $string)
    {
        $format = self::format($bytes);
        switch ($bytes) {
            case 1:
            case 2:
            case 4:
                return Arr::get(unpack($format, $string), 1);
            case 3:
                $unpack = unpack($format, $string);
                return 256*(256*Arr::get($unpack, 1)+Arr::get($unpack, 2))+Arr::get($unpack, 3);
            default:
                throw new Exception('Data size greater than max safe byte');
        }
    }

    /**
     * @return array
     */
    public static function bytesMaxSize()
    {
        $sizes = [];
        for($i = 1; $i <= self::MAX_BYTE; $i++){
            $sizes[] = [
                'max' => (2**($i*8)) - 1,
                'bytes' => $i,
            ];
        }

        return $sizes;
    }
}
