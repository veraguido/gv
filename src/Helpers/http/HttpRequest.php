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
        $this->requestParams = $this->getRequestParametersByType($this->requestType);
    }

    /**
     * @return array
     */
    private function getRequestParametersByType($type)
    {
        switch ($type) {
            case self::GET:
                return $_GET;
            break;
            case self::POST:
                return $_POST;
            break;
            case self::PUT:
            case self::DELETE:
                return parse_str(file_get_contents("php://input"), $paramArray);
            break;
        }
    }

    /**
     * @return mixed
     */
    public function getParameter($name)
    {
        return isset($this->requestParams[$name]) ? $this->requestParams[$name] : null;
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

    /**
     * @return string
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    public function setParameter($key, $value)
    {
        $this->requestParams[$key] = $value;
    }
}
