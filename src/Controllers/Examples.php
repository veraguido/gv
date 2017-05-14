<?php

namespace Gvera\Controllers;


use Gvera\Helpers\Session\Session;

class Examples extends GController
{
    public function index()
    {
        echo phpinfo();
        //$this->httpResponse->redirect("/Cars/tiju");
        //$this->httpResponse->notFound();
    }

    public function tiju()
    {
        $this->viewParams = array('asd' => 'iiiiuuuuuuuuujjjuuu');
    }

    public function tan()
    {

        Session::set("asd", 1);
        Session::toString();
        $count = Session::get('count') ? Session::get('count') : 1;

        echo $count;

        Session::set('count', ++$count);
    }
}