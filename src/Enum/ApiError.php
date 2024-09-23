<?php

namespace App\Enum;

enum ApiError: int
{
    case ALREADY_EXISTS = 409;
    case NOT_FOUND = 404;
    case INVALID_REQUEST = 400;
    case INTERNAL_ERROR = 500;

}
