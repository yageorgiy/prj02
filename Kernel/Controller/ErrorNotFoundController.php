<?php


namespace kernel\controller;


use Kernel\NotFoundResponse;
use Kernel\Request;
use Kernel\Response;
use Kernel\Templates;

class ErrorNotFoundController extends Controller
{
    public function processRequest(Request $request, Templates $templates): Response
    {
        return new NotFoundResponse();
    }
}