<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use GuzzleHttp\Psr7\Request;

trait HasPsrRequest
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        if ($this->request === null) {
            $this->request = new Request(request()->method(), request()->url(), request()->headers->all(), request()->getContent(true));
        }

        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
}
