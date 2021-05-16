<?php

namespace Gvera\Helpers\http;

use Gvera\Exceptions\NotFoundException;
use Gvera\Helpers\fileSystem\File;
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
    const PATCH = "PATCH";
    const DELETE = 'DELETE';
    const OPTIONS = 'OPTIONS';

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
     * @param $name
     * @return mixed
     */
    public function patch($name = null)
    {
        return $this->getFromStream($name);
    }

    /**
     * @param null $name
     * @return mixed
     */
    public function put($name = null)
    {
        return $this->getFromStream($name);
    }

    /**
     * @param null $name
     * @return mixed
     */
    public function delete($name = null)
    {
        return $this->getFromStream($name);
    }

    /**
     * @param $name
     * @return mixed
     */
    private function getFromStream(string $name)
    {
            $streamContent = [];
            parse_str(file_get_contents("php://input"), $streamContent);

        if (!isset($streamContent[$name])) {
            throw new NotFoundException('parameter not found');
        }

        return $streamContent[$name];
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
     * @param File $file
     * @return bool
     * @throws \Gvera\Exceptions\InvalidFileTypeException
     * @throws \Gvera\Exceptions\NotFoundException
     */
    public function moveFileToDirectory($directory, File $file)
    {
        return $this->fileManager->saveToFileSystem($directory, $file);
    }

    /**
     * @param $propertyName
     * @param string|null $changedName
     * @return File
     * @throws \Gvera\Exceptions\NotFoundException
     */
    public function getFileByPropertyName($propertyName, ?string $changedName = null):File
    {
        $this->fileManager->buildFilesFromSource($_FILES, $changedName);
        return $this->fileManager->getByName($propertyName);
    }

    public function getAuthDetails(): ?BasicAuthenticationDetails
    {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            return null;
        }

        return new BasicAuthenticationDetails($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
    }

    public function getIP():string
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}
