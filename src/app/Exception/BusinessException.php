<?php

namespace App\Exception;

/**
 * Class BusinessException
 *
 * This class represents a custom exception for business logic errors.
 * It extends the base Exception class and adds a user-friendly message.
 *
 * @package App\Exception
 */
class BusinessException extends \Exception
{
    private string $userMessage;

    public function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}
