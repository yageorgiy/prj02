<?php
namespace Kernel;

use Kernel\Database\DatabaseManager;
use Kernel\Parser\WikipediaParser;
use Kernel\Response\ErrorResponse;
use Kernel\Response\Response;

class Kernel
{
    private Router $router;
    private Templates $templates;
    private DatabaseManager $databaseManager;
    private Config $config;
    private WikipediaParser $wikipediaParser;

    public function __construct()
    {
        $this->config = new Config(__DIR__ . "/../environment.ini");
        $this->templates = new Templates();
        $this->router = new Router($this);
        $this->databaseManager = new DatabaseManager();
        $this->wikipediaParser = new WikipediaParser();
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
        $this->databaseManager->makeDatabase();

        // Setup wikipedia parser
        $this->wikipediaParser->init($this->config->get("api_mount"));

        // Process the request
        $request = new Request(
            parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),
            $_SERVER['REQUEST_METHOD'],
            $_GET,
            $_POST
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
        header("Content-type: {$response->getContentType()}");

        if ($response->getRedirect() != "")
            header("Location: {$response->getRedirect()}");

        echo $response->getContents();
    }

    /* Getters */

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @return Templates
     */
    public function getTemplates(): Templates
    {
        return $this->templates;
    }

    /**
     * @return DatabaseManager
     */
    public function getDatabaseManager(): DatabaseManager
    {
        return $this->databaseManager;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @return WikipediaParser
     */
    public function getWikipediaParser(): WikipediaParser
    {
        return $this->wikipediaParser;
    }




}