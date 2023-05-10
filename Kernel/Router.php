<?php


namespace Kernel;


use kernel\controller\Controller;
use kernel\controller\ErrorNotFoundController;
use kernel\controller\HomeController;
use Kernel\Response\Response;

class Router
{

    private Kernel $kernel;

    /**
     * Router constructor.
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function decide(Request $request): Response
    {
        $controller = match($request->getPath()) {
            "/" => new HomeController(),
            default => new ErrorNotFoundController()
        };

        return $controller->processRequest($request, $this->kernel);
    }

}