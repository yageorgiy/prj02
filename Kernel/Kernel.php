<?php
namespace Kernel;

use Kernel\Database\DatabaseManager;

class Kernel
{
    private Router $router;
    private Templates $templates;
    private DatabaseManager $databaseManager;
    private Config $config;

    public function __construct()
    {
        $this->config = new Config(__DIR__ . "/../environment.ini");
        $this->templates = new Templates();
        $this->router = new Router($this->templates);
        $this->databaseManager = new DatabaseManager();
    }

    /**
     * Handle current request
     */
    public function handle()
    {
        // Check for config errors
        if (!$this->config->checkForRequiredProperties()) {
            $this->respond(new ErrorResponse("Bad config"));
            return;
        }

        // Setup database & check for database errors
        if (!$this->databaseManager->setupConnection(
            $this->config->get("database_host"),
            $this->config->get("database_port"),
            $this->config->get("database_name"),
            $this->config->get("database_user"),
            $this->config->get("database_password")
        )) {
            $this->respond(new ErrorResponse("Database connection error"));
            return;
        }

        // Process the request
        $request = new Request(
            $_SERVER['REQUEST_URI'],
            $_SERVER['REQUEST_METHOD']
        );
        $response = $this->router->decide($request);
        $this->respond($response);
    }

    /**
     * Return web page contents
     */
    public function respond(Response $response)
    {
        http_response_code($response->getStatusCode());
        echo $response->getContents();
    }

}