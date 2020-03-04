<?php

namespace Gvera\Controllers\v0;

use Gvera\Controllers\GvController;
use Gvera\Helpers\http\Response;

class Index extends GvController
{
    public function index()
    {
        $this->httpResponse->response(new Response("2nd level index"));
    }
}
