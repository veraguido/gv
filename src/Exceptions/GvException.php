<?php

namespace Gvera\Exceptions;

use Throwable;

abstract class GvException extends \Exception
{
    protected $arguments;
    public function __construct(string $message, array $arguments = [], int $code = 0, Throwable $previous = null)
    {
        $this->arguments = $arguments;
        parent::__construct($message, $code, $previous);
    }

    public function getArguments()
    {
        return $this->arguments;
    }
}
