<?php

namespace Keygen\Exceptions;

use Keygen\Exceptions\Traits\BadConfigPathTrait;

use Exception as OriginalException;
use Throwable;

class Exception extends OriginalException
{
    use BadConfigPathTrait;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}