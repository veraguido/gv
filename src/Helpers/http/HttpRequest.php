<?php

namespace Gvera\Helpers\http;

use Gvera\Helpers\config\Config;

/**
 * Class HttpRequest
 * @package Gvera\Helpers\http
 * This is a request wrapper to manage params making an abstraction from the request type
 */
class HttpRequest
{

    private $requestType;
    private $requestParams = array();
    private $fileManager;
    private $serverRequest;

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const OPTIONS = 'OPTIONS';

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function setServerRequest($serverRequest) {
        $this->serverRequest = $serverRequest;
        $this->setHttpMethod($serverRequest->server['request_method']);
    }

    private function setHttpMethod($httpMethod) {
        $this->requestType = $httpMethod;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getParameter($name)
    {
        $req = strtolower($this->requestType);
        return $this->serverRequest->$req[$name];
    }

    /**
     * @return array|object
     */
    public function get($name = null)
    {
        $getArray = filter_input_array(INPUT_GET);
        return $name === null ? $getArray : $getArray[$name];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function post($name = null)
    {
        $postArray = filter_input_array(INPUT_POST);
        return $name === null ? $postArray : $postArray[$name];
    }

    /**
     * @param $name
     */
    public function put($name = null)
    {
        $this->getPutDeleteParameter($name);
    }

    /**
     * @param $name
     */
    public function delete($name = null)
    {
        $this->getPutDeleteParameter($name);
    }

    /**
     * @param $name
     * @return mixed
     */
    private function getPutDeleteParameter($name)
    {
        $putDeleteArray = [];
        parse_str(file_get_contents("php://input"), $putDeleteArray);
        return $name === null ? $putDeleteArray : $putDeleteArray[$name];
    }
    
    /**
     * @return boolean
     */
    public function isPost()
    {
        return $this->requestType == self::POST;
    }

    /**
     * @return boolean
     */
    public function isGet()
    {
        return $this->requestType == self::GET;
    }

    /**
     * @return boolean
     */
    public function isPut()
    {
        return $this->requestType == self::PUT;
    }

    /**
     * @return boolean
     */
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

    /**
     * @return boolean
     */
    public function isAjax()
    {
        return null !== $_SERVER['HTTP_X_REQUESTED_WITH'] &&
        $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * @param $directory
     * @param $uploadedFileName
     * @return bool
     * @throws \Gvera\Exceptions\InvalidFileTypeException
     * @throws \Gvera\Exceptions\NotFoundException
     */
    public function moveFileToDirectory($directory, $uploadedFileName)
    {
        $this->fileManager->buildFilesFromSource($_FILES);
        
        $file = $this->fileManager->getByName($uploadedFileName);
        return $this->fileManager->saveToFileSystem($directory, $file);
    }
}
