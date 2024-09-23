<?php

namespace App\Exception;

use App\Enum\ApiError;

class NotFoundException extends ApiException
{
    public function __construct(string $message, \Exception $previous = null)
    {
        parent::__construct("not found [{$message}]", ApiError::NOT_FOUND->value, $previous);
    }
}
