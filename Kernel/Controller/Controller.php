<?php


namespace kernel\controller;


use Kernel\Request;
use Kernel\Response;
use Kernel\Templates;

abstract class Controller
{

    abstract public function processRequest(Request $request, Templates $templates): Response;

}