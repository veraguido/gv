<?php

namespace Gvera\Helpers\http;

use Gvera\Helpers\config\Config;
use Gvera\Models\BasicAuthenticationDetails;

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

    private $putDeleteArray;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
        $this->requestType =  $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getParameter($name)
    {
        if (isset($this->requestParams[$name])) {
            return $this->requestParams[$name];
        }

        $req = strtolower($this->requestType);
        return $this->$req($name);
    }

    /**
     * @return array|object
     */
    public function get($name = null)
    {
        $getArray = filter_input_array(INPUT_GET);
        return (null === $name || !isset($getArray[$name]) || false === $getArray) ? null : $getArray[$name];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function post($name = null)
    {
        $postArray = filter_input_array(INPUT_POST);
        return (null === $name || !isset($postArray[$name]) || false === $postArray) ? null : $postArray[$name];
    }

    /**
     * @param null $name
     * @return mixed
     */
    public function put($name = null)
    {
        return $this->getPutDeleteParameter($name);
    }

    /**
     * @param null $name
     * @return mixed
     */
    public function delete($name = null)
    {
        return $this->getPutDeleteParameter($name);
    }

    /**
     * @param $name
     * @return mixed
     */
    private function getPutDeleteParameter($name)
    {
        if (null === $this->putDeleteArray) {
            $this->putDeleteArray = [];
            parse_str(file_get_contents("php://input"), $this->putDeleteArray);
        }

        $value = isset($this->putDeleteArray[$name]) ? $this->putDeleteArray[$name] : null;

        return $name === null ? $this->putDeleteArray : $value;
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
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
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

    public function getAuthDetails(): ?BasicAuthenticationDetails
    {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            return null;
        }

        return new BasicAuthenticationDetails($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
    }
}
