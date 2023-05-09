<?php


namespace kernel\controller;


use Kernel\BadRequestResponse;
use Kernel\Request;
use Kernel\Response;
use Kernel\Templates;

class HomeController extends Controller
{

    public function processRequest(Request $request, Templates $templates): Response
    {
        if($request->isGET())
            return new Response($templates->home(), 200);

        if(!$request->isPOST())
            return new BadRequestResponse();

        return new Response("...", 200);
    }
}