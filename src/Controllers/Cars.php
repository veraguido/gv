<?php

namespace Gvera\Controllers;


class Cars extends GController
{
    public function index() {

        $this->httpResponse->redirect("/Cars/tiju");
        //$this->httpResponse->notFound();
    }

    public function tiju() {
        $this->viewParams = array('asd' => 'iiiiuuuuuuuuujjjuuu');
    }
}