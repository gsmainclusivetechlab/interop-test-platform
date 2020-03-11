<?php declare(strict_types=1);

namespace App\Testing;

use PHPUnit\Framework\TestSuite;
use Psr\Http\Message\ResponseInterface;

class ResponseTestSuite extends TestSuite
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;

        foreach ($this->tests as $test) {
            if (!$test instanceof ResponseTestCase) {
                continue;
            }

            $test->setResponse($response);
        }
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse():? ResponseInterface
    {
        return $this->response;
    }
}
