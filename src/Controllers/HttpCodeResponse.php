<?php

namespace Gvera\Controllers;


class HttpCodeResponse extends GvController
{
    public function resourceNotFound()
    {
        $this->httpResponse->asJson();
        $this->viewParams = array('code' => '404', 'message' => 'resource not found.');
        $this->httpResponse->notFound();
    }
}