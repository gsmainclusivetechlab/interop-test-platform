<?php declare(strict_types=1);

namespace App\Exceptions;

use App\Models\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MessageMismatchException extends HttpException
{
    /**
     * @var Session|null
     */
    protected $session;

    /**
     * MessageMismatchException constructor.
     * @param Session|null $session
     * @param int $statusCode
     * @param string|null $message
     */
    public function __construct(
        ?Session $session,
        int $statusCode,
        string $message = null
    ) {
        parent::__construct($statusCode, $message);
        $this->session = $session;
    }

    /**
     * @return Session|null
     */
    public function getSession()
    {
        return $this->session;
    }
}
