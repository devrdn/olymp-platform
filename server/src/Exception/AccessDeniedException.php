<?php

namespace App\Exception;

use Exception;

class AccessDeniedException extends AbstractJsonException {
    private const MESSAGE = "access denied";

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE);
    }
}