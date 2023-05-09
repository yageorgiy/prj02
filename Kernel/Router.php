<?php


namespace Kernel;


use kernel\controller\Controller;
use kernel\controller\ErrorNotFoundController;
use kernel\controller\HomeController;

class Router
{

    private Templates $templates;

    /**
     * Router constructor.
     */
    public function __construct(Templates $templates)
    {
        $this->templates = $templates;
    }

    public function decide(Request $request): Response
    {
        $controller = match($request->getPath()) {
            "/" => new HomeController(),
            default => new ErrorNotFoundController()
        };

        return $controller->processRequest($request, $this->templates);
    }

}