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

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const OPTIONS = 'OPTIONS';

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
        $this->requestType = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
    }

    /**
     * @return mixed
     */
    public function getParameter($name)
    {
        $req = strtolower($this->requestType);
        return $this->$req($name);
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
     * @return array|object
     */
    public function post($name = null)
    {
        $postArray = filter_input_array(INPUT_POST);
        return $name === null ? $postArray : $postArray[$name];
    }

    /**
     * @return array|object
     */
    public function put($name = null)
    {
        $this->getPutDeleteParameter($name);
    }

    /**
     * @return array|object
     */
    public function delete($name = null)
    {
        $this->getPutDeleteParameter($name);
    }

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
     * @return bool
     * @throws InvalidFileTypeException
     * @throws NotFoundException
     */
    public function moveFileToDirectory($directory, $uploadedFileName)
    {
        $this->fileManager->buildFilesFromSource($_FILES);
        
        $file = $this->fileManager->getByName($uploadedFileName);
        return $this->fileManager->saveToFileSystem($directory, $file);
    }
}
