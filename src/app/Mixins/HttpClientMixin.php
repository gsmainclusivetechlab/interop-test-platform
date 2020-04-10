<?php declare(strict_types=1);

namespace App\Mixins;

class HttpClientMixin
{
    /**
     * @return callable
     */
    public function prepare()
    {
        return function() {
            return $this;
        };
    }
}
