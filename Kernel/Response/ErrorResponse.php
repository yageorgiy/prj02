<?php


namespace Kernel;


class ErrorResponse extends Response
{
    /**
     * ErrorResponse constructor.
     */
    public function __construct(string $error)
    {
        parent::__construct("<h1>$error</h1>", 500);
    }
}