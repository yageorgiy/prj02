<?php


namespace Kernel\controller;


use Kernel\Kernel;
use Kernel\Response\NotFoundResponse;
use Kernel\Request;
use Kernel\Response\Response;

class ErrorNotFoundController extends Controller
{
    public function processRequest(Request $request, Kernel $kernel): Response
    {
        return new NotFoundResponse();
    }
}