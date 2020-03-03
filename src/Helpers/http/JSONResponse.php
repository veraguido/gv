<?php

namespace Gvera\Helpers\http;

class JSONResponse extends Response
{
    /**
     * JSONResponse constructor.
     * @param array $content
     * @param string $code
     * @param string $auth
     */
    public function __construct(array $content, string $code = Response::HTTP_RESPONSE_OK, string $auth = '')
    {
        parent::__construct(json_encode($content), Response::CONTENT_TYPE_JSON, $code, $auth);
    }
}