<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

trait HasRequest
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        if ($this->request === null) {
            $this->request = new Request(request()->method(), request()->url(), request()->headers->all(), request()->getContent(true));
        }

        return $this->request;
    }

    /**
     * @param RequestInterface $request
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }
}
