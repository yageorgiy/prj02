<?php


namespace Kernel;


class Response
{
    private string $contents;
    private int $statusCode;

    /**
     * Response constructor.
     * @param string $contents
     * @param int $statusCode
     */
    public function __construct(string $contents, int $statusCode)
    {
        $this->contents = $contents;
        $this->statusCode = $statusCode;
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * @param string $contents
     */
    public function setContents(string $contents): void
    {
        $this->contents = $contents;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

}