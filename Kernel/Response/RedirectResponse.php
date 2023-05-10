<?php


namespace Kernel\Response;


class RedirectResponse extends Response
{
    public function __construct(string $redirect)
    {
        parent::__construct("", 301);
        $this->redirect = $redirect;
    }

}