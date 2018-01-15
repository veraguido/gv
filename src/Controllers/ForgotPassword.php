<?php
namespace Gvera\Controllers;


class ForgotPassword extends GvController
{
    public function index()
    {
        echo "asd";
    }

    public function newForgotPassword()
    {
        if (!$this->httpRequest->isPost()) {
            $this->httpResponse->redirect('/forgotpassword');
        }
    }

}