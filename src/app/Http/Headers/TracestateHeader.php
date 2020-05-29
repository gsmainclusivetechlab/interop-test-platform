<?php declare(strict_types=1);

namespace App\Http\Headers;

use InvalidArgumentException;

class TracestateHeader
{
    const NAME = 'tracestate';

    /**
     * @var array
     */
    protected $vendors = [];

    /**
     * @param string $tracestate
     */
    public function __construct($tracestate = '')
    {
        if ($tracestate != '') {
            $parts = $this->parse($tracestate);

            if ($parts === false) {
                throw new InvalidArgumentException(
                    "Unable to parse tracestate: $tracestate"
                );
            }

            $this->applyParts($parts);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $parts = [];

        foreach ($this->vendors as $key => $value) {
            $parts[] = implode('=', [$key, base64_encode(json_encode($value))]);
        }

        return implode(',', $parts);
    }

    /**
     * @param string $key
     * @return bool|string
     */
    public function getVendor($key)
    {
        return $this->vendors[$key] ?? false;
    }

    /**
     * @return array
     */
    public function getVendors()
    {
        return $this->vendors;
    }

    /**
     * @param string $key
     * @param string $value
     * @return TracestateHeader
     */
    public function withVendor($key, $value)
    {
        $new = clone $this;
        $new->vendors[$this->filterVendorKey($key)] = $this->filterVendorValue(
            $value
        );

        return $new;
    }

    /**
     * @param string $key
     * @return TracestateHeader
     */
    public function withoutVendor($key)
    {
        $new = clone $this;
        unset($new->vendors[$key]);

        return $new;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasVendor($key)
    {
        return isset($this->vendors[$key]);
    }

    /**
     * @return bool
     */
    public function hasVendors()
    {
        return !empty($this->vendors);
    }

    /**
     * @param string $spanId
     * @return bool
     */
    public function hasVendorWithSpanId($spanId)
    {
        foreach ($this->vendors as $vendor) {
            if (isset($vendor['spanId']) && $vendor['spanId'] == $spanId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $tracestate
     * @return array|false
     */
    protected function parse($tracestate)
    {
        $parts = explode(',', $tracestate);

        if (!count($parts)) {
            return false;
        }

        return $parts;
    }

    /**
     * @param array $parts
     */
    protected function applyParts(array $parts)
    {
        foreach ($parts as $part) {
            $vendor = explode('=', $part, 2);

            if (count($vendor) != 2) {
                throw new InvalidArgumentException('Vendor is invalid');
            }

            [$key, $value] = $vendor;

            $this->vendors[
                $this->filterVendorKey($key)
            ] = $this->filterVendorValue($value);
        }
    }

    /**
     * @param array $parts
     * @return TracestateHeader
     */
    public static function fromParts(array $parts)
    {
        $tracestate = new self();
        $tracestate->applyParts($parts);

        return $tracestate;
    }

    /**
     * @param string $vendorKey
     * @return string
     */
    protected function filterVendorKey($vendorKey)
    {
        if (!preg_match('/^[a-z_\-\*\/0-9]+$/', $vendorKey)) {
            throw new InvalidArgumentException('Vendor key is invalid');
        }

        return $vendorKey;
    }

    /**
     * @param string $vendorValue
     * @return mixed
     */
    protected function filterVendorValue($vendorValue)
    {
        if (
            !($vendorValue = base64_decode($vendorValue)) ||
            !($vendorValue = json_decode($vendorValue, true))
        ) {
            throw new InvalidArgumentException('Vendor value is invalid');
        }

        return $vendorValue;
    }
}
