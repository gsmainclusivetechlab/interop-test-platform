<?php declare(strict_types=1);

namespace App\Http\Headers;

use InvalidArgumentException;

class TraceparentHeader
{
    const NAME = 'traceparent';

    /**
     * @var string
     */
    protected $version;
    /**
     * @var string
     */
    protected $traceId;
    /**
     * @var string
     */
    protected $parentId;
    /**
     * @var string
     */
    protected $traceFlags;

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
     * @return string
     */
    public function __toString()
    {
        return implode('-', array_filter([
            $this->version,
            $this->traceId,
            $this->parentId,
            $this->traceFlags,
        ]));
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return TraceparentHeader
     */
    public function withVersion($version)
    {
        $new = clone $this;
        $new->version = $this->filterVersion($version);

        return $new;
    }

    /**
     * @return string
     */
    public function getTraceId()
    {
        return $this->traceId;
    }

    /**
     * @param string $traceId
     * @return TraceparentHeader
     */
    public function withTraceId($traceId)
    {
        $new = clone $this;
        $new->traceId = $this->filterTraceId($traceId);

        return $new;
    }

    /**
     * @return string
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param string $parentId
     * @return TraceparentHeader
     */
    public function withParentId($parentId)
    {
        $new = clone $this;
        $new->parentId = $this->filterParentId($parentId);

        return $new;
    }

    /**
     * @return string
     */
    public function getTraceFlags()
    {
        return $this->traceFlags;
    }

    /**
     * @param string $traceFlags
     * @return TraceparentHeader
     */
    public function withTraceFlags($traceFlags)
    {
        $new = clone $this;
        $new->traceFlags = $this->filterParentId($traceFlags);

        return $new;
    }

    /**
     * @param $traceparent
     * @return array|false
     */
    protected function parse($traceparent)
    {
        $parts = explode('-', $traceparent);

        switch (count($parts)) {
            case 2:
                return array_combine(['version', 'trace-id'], $parts);
            case 3:
                return array_combine(['version', 'trace-id', 'parent-id'], $parts);
            case 4:
                return array_combine(['version', 'trace-id', 'parent-id', 'trace-flags'], $parts);
            default:
                return false;
        }
    }

    /**
     * @param array $parts
     */
    protected function applyParts(array $parts)
    {
        $this->version = isset($parts['version']) ? $this->filterVersion($parts['version']) : '';
        $this->traceId = isset($parts['trace-id']) ? $this->filterTraceId($parts['trace-id']) : '';
        $this->parentId = isset($parts['parent-id']) ? $this->filterParentId($parts['parent-id']) : '';
        $this->traceFlags = isset($parts['trace-flags']) ? $this->filterTraceFlags($parts['trace-flags']) : '';
    }

    /**
     * @param array $parts
     * @return TraceparentHeader
     */
    public static function fromParts(array $parts)
    {
        $traceparent = new self();
        $traceparent->applyParts($parts);

        return $traceparent;
    }

    /**
     * @param string $version
     * @return string
     */
    protected function filterVersion($version)
    {
        if (!preg_match('/^[0-9]{2}$/', $version)) {
            throw new InvalidArgumentException('Version is invalid');
        }

        return $version;
    }

    /**
     * @param string $traceId
     * @return string
     */
    protected function filterTraceId($traceId)
    {
        if (!preg_match('/^[a-z0-9]{32}$/', $traceId)) {
            throw new InvalidArgumentException('Trace ID is invalid');
        }

        return $traceId;
    }

    /**
     * @param string $parentId
     * @return string
     */
    protected function filterParentId($parentId)
    {
        if (!preg_match('/^[a-z0-9]{16}$/', $parentId)) {
            throw new InvalidArgumentException('Parent ID is invalid');
        }

        return $parentId;
    }

    /**
     * @param string $traceFlags
     * @return string
     */
    protected function filterTraceFlags($traceFlags)
    {
        if (!preg_match('/^[0-9]{2}$/', $traceFlags)) {
            throw new InvalidArgumentException('Trace flags is invalid');
        }

        return $traceFlags;
    }
}
