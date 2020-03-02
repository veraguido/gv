<?php

namespace Gvera\Helpers\http;

class JSONResponse extends Response
{
    /**
     * JSONResponse constructor.
     */
    public function __construct($code)
    {
        parent::__construct(Response::CONTENT_TYPE_JSON, $code);
    }
}