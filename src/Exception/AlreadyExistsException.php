<?php

namespace App\Exception;

use App\Enum\ApiError;

class AlreadyExistsException extends ApiException
{
    public function __construct(string $message, \Exception $previous = null)
    {
        parent::__construct("already exists [{$message}]", ApiError::ALREADY_EXISTS->value, $previous);
    }
}
