<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 14/05/17
 * Time: 10:21
 */

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