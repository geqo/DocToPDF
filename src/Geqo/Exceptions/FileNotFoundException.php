<?php

namespace Geqo\Exceptions;

use Exception;
use Throwable;

class FileNotFoundException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}