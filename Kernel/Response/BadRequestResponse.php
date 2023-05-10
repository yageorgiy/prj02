<?php


namespace Kernel\Response;


class BadRequestResponse extends Response
{
    public function __construct()
    {
        parent::__construct("<h1>400 Bad Request</h1>", 400);
    }

}