<?php

namespace Gvera\Helpers\http;

/**
 * Class HttpRequest
 * @package Gvera\Helpers\http
 * This is a request wrapper to manage params making an abstraction from the request type
 */
class HttpRequest
{

    private $requestType;
    private $requestParams = array();

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const OPTIONS = 'OPTIONS';

    public function __construct()
    {
        $this->requestType = $_SERVER['REQUEST_METHOD'];
    }

    public function getParameters()
    {
        $paramArray = array();
        if ($this->isGet()) {
            $paramArray = $_GET;
        }

        if ($this->isPost()) {
            $paramArray = $_POST;
        }

        if ($this->isPut() || $this->isDelete()) {
            parse_str(file_get_contents("php://input"), $paramArray);
        }

        return array_merge($this->requestParams, $paramArray);
    }

    public function getParameter($name)
    {
        return isset($this->getParameters()[$name]) ? $this->getParameters()[$name] : null;
    }

    public function isPost()
    {
        return $this->requestType == self::POST;
    }

    public function isGet()
    {
        return $this->requestType == self::GET;
    }

    public function isPut()
    {
        return $this->requestType == self::PUT;
    }

    public function isDelete()
    {
        return $this->requestType == self::DELETE;
    }

    public function getRequestType()
    {
        return $this->requestType;
    }

    public function setParameter($key, $value)
    {
        $this->requestParams[$key] = $value;
    }
}
