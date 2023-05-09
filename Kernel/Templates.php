<?php


namespace Kernel;


class Templates
{
    /**
     * Templates constructor.
     */
    public function __construct()
    {
    }

    /**
     * Load specific template
     * @param string $templateName
     * @param array $params
     * @return string
     */
    private function load(string $templateName, array $params = []): string
    {
        // Try to read file without warnings
        $contents = @file_get_contents(__DIR__ . "/../templates/" . $templateName);

        if($contents == false)
            return "";

        foreach ($params as $paramName => $paramValue){
            $contents = str_replace("{" . $paramName . "}", $paramValue, $contents);
        }

        return $contents;
    }

    /**
     * Load home page template
     * @return string
     */
    public function home(): string
    {
        return $this->load("home.html");
    }

    /**
     * Load error page template
     * @param string $error
     * @return string
     */
    public function error(string $error): string
    {
        return $this->load("error.html", [
            "ERROR_DESCRIPTION" => $error
        ]);
    }

}