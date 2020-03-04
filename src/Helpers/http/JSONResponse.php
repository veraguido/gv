<?php

namespace Gvera\Helpers\http;

class JSONResponse extends Response
{
    /**
     * JSONResponse constructor.
     * @param array $content
     * @param string $httpCode
     * @param string $auth
     */
    public function __construct(array $content, string $httpCode = Response::HTTP_RESPONSE_OK, string $auth = '')
    {
        parent::__construct(json_encode($content), Response::CONTENT_TYPE_JSON, $httpCode, $auth);
    }
}
