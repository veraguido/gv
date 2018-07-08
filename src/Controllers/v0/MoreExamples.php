<?php
namespace Gvera\Controllers\v0;

use Gvera\Controllers\GvController;

class MoreExamples extends GvController
{
    public function index()
    {
        $this->httpResponse->response("2nd level controller!");
    }

    public function other()
    {
        $this->httpResponse->response("another!");
    }
}
