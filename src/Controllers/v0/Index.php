<?php

namespace Gvera\Controllers\v0;

use Gvera\Controllers\GvController;

class Index extends GvController
{
    public function index()
    {
        $this->httpResponse->response("2nd level index");
    }
}
