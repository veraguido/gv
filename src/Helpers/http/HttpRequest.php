<?php

namespace Gvera\Helpers\http;

use Exception;
use Gvera\Exceptions\InvalidFileTypeException;
use Gvera\Exceptions\NotFoundException;
use Gvera\Helpers\fileSystem\File;
use Gvera\Helpers\fileSystem\FileManager;
use Gvera\Models\BasicAuthenticationDetails;
use ReflectionException;

/**
 * Class HttpRequest
 * @package Gvera\Helpers\http
 * This is a request wrapper to manage params making an abstraction from the request type
 */
class HttpRequest
{

    private string $requestType;
    private array $requestParams = array();
    private FileManager $fileManager;
    private HttpRequestValidator $httpRequestValidator;

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = "PATCH";
    const DELETE = 'DELETE';
    const OPTIONS = 'OPTIONS';


    public function __construct(FileManager $fileManager, HttpRequestValidator $httpRequestValidator)
    {
        $this->fileManager = $fileManager;
        $this->httpRequestValidator = $httpRequestValidator;
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
     * @param null $name
     * @return mixed|null
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
     * @throws NotFoundException
     */
    public function patch($name = null)
    {
        return $this->getParameterFromStream($name);
    }

    /**
     * @param null $name
     * @return mixed
     * @throws NotFoundException
     */
    public function put($name = null)
    {
        return $this->getParameterFromStream($name);
    }

    /**
     * @param null $name
     * @return mixed
     * @throws NotFoundException
     */
    public function delete($name = null)
    {
        return $this->getParameterFromStream($name);
    }

    /**
     * @param string $name
     * @return string
     * @throws NotFoundException
     */
    private function getParameterFromStream(string $name): string
    {
            $streamContent = [];
            parse_str(file_get_contents("php://input"), $streamContent);

        if (!isset($streamContent[$name])) {
            throw new NotFoundException('parameter not found');
        }

        return $streamContent[$name];
    }

    private function getParametersFromStream(): array
    {
        $streamContent = [];
        parse_str(file_get_contents("php://input"), $streamContent);
        return $streamContent;
    }

    private function getParametersFromRequest(): array
    {
        return $_REQUEST;
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->requestType == self::POST;
    }

    /**
     * @return boolean
     */
    public function isGet(): bool
    {
        return $this->requestType == self::GET;
    }

    /**
     * @return boolean
     */
    public function isPut(): bool
    {
        return $this->requestType == self::PUT;
    }

    /**
     * @return boolean
     */
    public function isPatch(): bool
    {
        return $this->requestType == self::PATCH;
    }

    /**
     * @return boolean
     */
    public function isDelete(): bool
    {
        return $this->requestType == self::DELETE;
    }

    /**
     * @return string
     */
    public function getRequestType(): string
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
    public function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * @param $directory
     * @param File $file
     * @return bool
     * @throws NotFoundException
     * @throws InvalidFileTypeException
     */
    public function moveFileToDirectory($directory, File $file): bool
    {
        return $this->fileManager->saveToFileSystem($directory, $file);
    }

    /**
     * @param $propertyName
     * @param string|null $changedName
     * @return File
     * @throws NotFoundException
     */
    public function getFileByPropertyName($propertyName, ?string $changedName = null): File
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

    public function getIP(): string
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @return bool
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws Exception
     */
    public function validate(): bool
    {
        $traces = debug_backtrace();

        if (!isset($traces[1])) {
            throw new Exception('incorrect method calling validate');
        }
        $method = $traces[1]['function'];
        $fullClassPath = $traces[1]['class'];
        $reflectionClass = new \ReflectionClass($fullClassPath);
        $controllersClassName = $reflectionClass->getShortName();

        $fields =
            $this->isGet() || $this->isPost() ?
            $this->getParametersFromRequest() :
            $this->getParametersFromStream();

        return $this->httpRequestValidator->validate($controllersClassName, $method, $fields, getallheaders());
    }
}
