<?php
namespace Gvera\Controllers;

use Throwable;

class Doc extends GvController
{
    public function index()
    {
        try {
            $this->checkApiAuthentication();
        } catch (Throwable $e) {
            $this->unauthorizedBasicAuth();
            $this->httpResponse->asJson();
            $this->httpResponse->response(['message' => $e->getMessage()]);
            exit;
        }
    }
}
