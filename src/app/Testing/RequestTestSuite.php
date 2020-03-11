<?php declare(strict_types=1);

namespace App\Testing;

use PHPUnit\Framework\TestSuite;
use Psr\Http\Message\ServerRequestInterface;

class RequestTestSuite extends TestSuite
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

        foreach ($this->tests as $test) {
            if (!$test instanceof RequestTestCase) {
                continue;
            }

            $test->setRequest($request);
        }
    }

    /**
     * @return ServerRequestInterface|null
     */
    public function getRequest():? ServerRequestInterface
    {
        return $this->request;
    }
}
