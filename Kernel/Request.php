<?php


namespace Kernel;


class Request
{
    private string $path;
    private string $method;

    /**
     * Request constructor.
     * @param string $path
     * @param string $method
     */
    public function __construct(string $path, string $method)
    {
        $this->path = $path;
        $this->method = $method;
    }


    public function isGET()
    {
        return $this->method == "GET";
    }

    public function isPOST()
    {
        return $this->method == "POST";
    }


    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

}