<?php


namespace Kernel\controller;


use Kernel\Kernel;
use Kernel\Request;
use Kernel\Response\Response;
use Kernel\Templates;

abstract class Controller
{

    abstract public function processRequest(Request $request, Kernel $kernel): Response;

}