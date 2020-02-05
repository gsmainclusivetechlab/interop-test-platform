<?php

namespace App\Http\Headers;

use InvalidArgumentException;

class Traceparent
{
    /**
     * @var string
     */
    protected $version;

    /**
     * @param string $traceparent
     */
    public function __construct($traceparent = '')
    {
        if ($traceparent != '') {
            $parts = $this->parse($traceparent);

            if ($parts === false) {
                throw new InvalidArgumentException("Unable to parse traceparent: $traceparent");
            }

            $this->applyParts($parts);
        }
    }

    /**
     * @param $traceparent
     * @return array|false
     */
    protected function parse($traceparent)
    {
        $parts = explode('-', $traceparent);

        if (count($parts) != 4) {
            return false;
        }

        return array_combine(['version', 'trace-id', 'parent-id', 'trace-flags'], $parts);
    }

    /**
     * @param array $parts
     */
    protected function applyParts(array $parts)
    {
        $this->version = isset($parts['version']) ? $this->filterVersion($parts['version']) : '';

        dd($parts);
    }

    /**
     * @param string $version
     * @return string
     */
    protected function filterVersion($version)
    {
        if (!is_string($version)) {
            throw new InvalidArgumentException('Version is invalid');
        }

        dd(strlen($version));

        return $version;
    }
}
