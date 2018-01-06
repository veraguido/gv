<?php

namespace Gvera\Controllers;


class Index extends GvController
{
    public function index()
    {
        $this->httpResponse->asJson();
        echo json_encode(array('gv' => array("version" => "1.0")));
    }
}