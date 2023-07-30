<?php

namespace App\Exception;

use Exception;

class AbstractJsonException extends Exception implements \JsonSerializable {

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'error' => $this->message,
        ];
    }
}