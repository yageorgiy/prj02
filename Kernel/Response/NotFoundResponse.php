<?php


namespace Kernel\Response;


class NotFoundResponse extends Response
{
    public function __construct()
    {
        parent::__construct("<h1>404 Not Found</h1>", 404);
    }

}