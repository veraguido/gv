<?php

namespace Gvera\Helpers\http;

class JSONResponse extends Response
{
    /**
     * JSONResponse constructor.
     * @param string $content
     * @param string $code
     */
    public function __construct(array $content, string $code = Response::HTTP_RESPONSE_OK)
    {
        parent::__construct(json_encode($content), Response::CONTENT_TYPE_JSON, $code);
    }
}