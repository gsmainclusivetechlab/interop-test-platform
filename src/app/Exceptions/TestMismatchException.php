<?php declare(strict_types=1);

namespace App\Exceptions;

use App\Models\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TestMismatchException extends HttpException
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * TestMismatchException constructor.
     * @param Session $session
     * @param int $statusCode
     * @param string|null $message
     */
    public function __construct(Session $session, int $statusCode, string $message = null)
    {
        parent::__construct($statusCode, $message);
        $this->session = $session;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }
}
