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
                return filter_input_array(INPUT_GET);
            case self::POST:
                return filter_input_array(INPUT_POST);
            case self::PUT:
            case self::DELETE:
                return parse_str(file_get_contents("php://input"), $paramArray);
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
