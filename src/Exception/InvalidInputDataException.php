<?php

namespace App\Exception;

use App\Enum\ApiError;

class InvalidInputDataException extends ApiException
{
    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct("invalid input data [{$message}]", ApiError::INVALID_REQUEST->value, $previous);
    }
}
