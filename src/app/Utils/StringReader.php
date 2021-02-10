<?php

namespace App\Utils;

use Arr;

class StringReader
{
    protected $string;

    protected $cursor = 0;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public static function from(string $string): StringReader
    {
        return new static($string);
    }

    public function read(int $length): string
    {
        $result = substr($this->string, $this->cursor, $length);
        $this->cursor += $length;

        return $result;
    }

    public function readUInt64(bool $unpack = true): string
    {
        $string = $this->read(8);

        return $unpack ? Arr::get(unpack('J', $string), 1) : $string;
    }

    /**
     * @param bool $unpack
     *
     * @return int|string
     */
    public function readUInt8Number(bool $unpack = true)
    {
        $string = $this->read(1);

        return $unpack ? Arr::get(unpack('C', $string), 1) : $string;
    }

    public function readVarOctetString(): string
    {
        $length = $this->readUInt8Number();

        return $this->read($length);
    }
}
