<?php declare(strict_types=1);

namespace App\Testing;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ResponseTestCase extends TestCase
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
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse():? ResponseInterface
    {
        return $this->response;
    }
}
