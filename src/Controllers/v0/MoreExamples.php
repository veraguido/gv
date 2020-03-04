<?php
namespace Gvera\Controllers\v0;

use Gvera\Controllers\GvController;
use Gvera\Helpers\http\Response;

class MoreExamples extends GvController
{
    public function index()
    {
        $this->httpResponse->response(new Response("2nd level controller!"));
    }

    public function other()
    {
        $this->httpResponse->response(new Response("another!"));
    }
}
