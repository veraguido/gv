<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 12/05/17
 * Time: 15:54
 */

namespace Gvera\Controllers;


class Index extends GController
{
    public function index()
    {
        $this->httpResponse->asJson();
        echo json_encode(array('gv' => array("version" => "1.0")));
    }
}