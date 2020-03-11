<?php declare(strict_types=1);

namespace App\Testing;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class RequestTestCase extends TestCase
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @param ServerRequestInterface $request
     */
    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return ServerRequestInterface|null
     */
    public function getRequest():? ServerRequestInterface
    {
        return $this->request;
    }
}
