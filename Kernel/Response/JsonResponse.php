<?php


namespace Kernel\Response;


class JsonResponse extends Response
{
    /**
     * ErrorResponse constructor.
     */
    public function __construct(array $data, int $statusCode)
    {
        parent::__construct(@json_encode($data) ?? [], $statusCode);
        $this->setContentType("application/json");
    }
}