<?php

namespace Revo\ComoSdk\Exceptions;

use RuntimeException;

class ComoException extends RuntimeException
{
    public function __construct(string $message)
    {
        $this->message = $message;
    }
}