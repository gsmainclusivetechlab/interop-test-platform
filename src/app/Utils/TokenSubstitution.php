<?php

namespace App\Utils;

/**
 * Class TokenSubstitution
 * @package App\Utils
 */
class TokenSubstitution
{
    /**
     * @var array
     */
    protected $tokens = [];

    /**
     * @param array $tokens
     */
    public function __construct(array $tokens = [])
    {
        $this->tokens = $tokens;
    }

    /**
     * @param string $content
     * @return string
     */
    public function replace(string $content)
    {
        return preg_replace_callback(
            '/\\$\{([^\}]+)\}/',
            function ($matches) {
                return $this->tokens[$matches[1]] ?? $matches[0];
            },
            $content
        );
    }
}
