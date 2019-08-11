<?php

namespace Gvera\Services;

use Gvera\Controllers\GvController;
use Gvera\Exceptions\InvalidControllerException;
use Gvera\Exceptions\NotFoundException;
use Gvera\Helpers\annotations\AnnotationUtil;
use PHPUnit\Runner\Exception;

/**
 * @class
 * This class will be in charge of generating the controller's lifecycle
 */
class ControllerService
{
    const CONTROLLERS_PREFIX = 'Gvera\\Controllers\\';

    private $method = 'index';
    private $controllerFinalName;
    
    private $uriData;
    private $controllerAutoloadingNames;

    private $diContainer;

    /**
     * @param $diContainer
     * @param $uriData
     * @throws \Exception
     * @return void
     */
    public function startControllerLifecyle($diContainer, $uriData)
    {
        $this->diContainer = $diContainer;
        $this->uriData = $uriData;
        $this->generateRegularControllerLifecycle();
    }

    /**
     * @param $controllerName
     * @param $methodName
     * @throws \Exception
     * @return void
     */
    public function generateSpecificControllerLifeCycle($controllerName, $methodName)
    {
        $this->generateControllerLifecycle($controllerName, $methodName);
    }

    /**
     * @param $diContainer
     * @throws \Exception
     * @return void
     */
    public function redirectToDefault($diContainer)
    {
        $this->diContainer = $diContainer;
        $response = $diContainer->get('httpResponse');
        $response->redirect('/' . GvController::DEFAULT_CONTROLLER);
    }

    /**
     * @throws \Exception
     * @return void
     */
    private function generateRegularControllerLifecycle()
    {
        $uriPath = $this->uriData;

        if (!isset($uriPath)) {
            throw new Exception('Url is malformed');
        }

        $uriArray = explode('/', $uriPath);
        $apiVersions = $this->getApiVersions();

        $this->generateControllerLifeCycleBasedOnGivenData($uriArray, $apiVersions);
    }

    /**
     * @return array
     * if there're versions (subcontrollers in the controllers directory, get them and return the autoloading names)
     */
    private function getApiVersions()
    {
        $versions = [];
        foreach ($this->controllerAutoloadingNames as $key => $controller) {
            if (is_array($controller)) {
                $versions[$key] = $controller;
            }
        }

        return $versions;
    }

    /**
     * @param $uriArray
     * @param $apiVersions
     * @throws \Exception
     */
    private function generateControllerLifeCycleBasedOnGivenData($uriArray, $apiVersions)
    {
        //if a version apply, go through that specific path
        if (array_key_exists($uriArray[1], $apiVersions)) {
            $this->generateControllerLifecycle(
                isset($uriArray[2]) ? $uriArray[2] : GvController::DEFAULT_CONTROLLER,
                $this->getValidMethodName(3, $uriArray),
                $uriArray[1]
            );
            return;
        }

        //if it doesn't go through the regular path
        $this->generateControllerLifecycle(
            $uriArray[1],
            $this->getValidMethodName(2, $uriArray)
        );
    }

    /**
     * @param $index
     * @param $uriArray
     * @return string
     */
    private function getValidMethodName($index, $uriArray)
    {
        return isset($uriArray[$index]) ? $uriArray[$index] : GvController::DEFAULT_METHOD;
    }

    /**
     * @param $controller
     * @param $method
     * @throws \Exception
     */
    private function generateControllerLifecycle($controller, $method, $version = null)
    {
        $this->controllerFinalName = $this->getControllerFinalName($controller, $version);
        $this->method = $this->getMethodFinalName($method);
        $controller = $this->getValidControllerClassName($this->controllerFinalName, $version);
        $this->initializeControllerInstance($controller);
    }

    /**
     * @param $rawName
     * @return string
     * If no Controller/Method is specified it will fallback to the default controller (Index controller)
     */
    private function getControllerFinalName(string $rawName = null, $version)
    {
        if (empty($rawName)) {
            return GvController::DEFAULT_CONTROLLER;
        }

        if (isset($version)) {
            return $this->getAutoloadedControllerName($this->controllerAutoloadingNames[$version], $rawName);
        }

        return $this->getAutoloadedControllerName($this->controllerAutoloadingNames, $rawName);
    }

    /**
     * @param $autoloadedArray
     * @param $name
     * @return mixed
     */
    private function getAutoloadedControllerName($autoloadedArray, $name)
    {
        $lowercaseRawName = strtolower($name);
        if (!array_key_exists($lowercaseRawName, $autoloadedArray)) {
            return $name;
        }

        return $autoloadedArray[$lowercaseRawName];
    }

    /**
     * @param null $methodName
     * @return string
     */
    private function getMethodFinalName($methodName = null)
    {
        //remove http get params if are present
        $methodName = explode('?', $methodName)[0];

        //if there's no method assigned then return the default method call.
        return ($methodName === null || $methodName == '') ? GvController::DEFAULT_METHOD : $methodName;
    }

    /**
     * @param $controllerName
     * @return string|null
     * @throws \Exception
     * All controllers should extend from GvController. By default if a Controller does not exist
     */
    private function getValidControllerClassName($controllerName, $version)
    {
        if ($controllerName == "GvController") {
            throw new InvalidControllerException('GvController is not a valid controller');
        }

        $versionPath = isset($version) ? $version . "\\" : "";

        if (class_exists(self::CONTROLLERS_PREFIX . $versionPath . $controllerName)) {
            return self::CONTROLLERS_PREFIX . $versionPath . $controllerName;
        }

        throw new NotFoundException("Resource not found");
    }

    /**
     * @param $controllerFullName
     * @throws \Exception
     */
    private function initializeControllerInstance($controllerFullName)
    {
        $controllerInstance = new $controllerFullName($this->diContainer, $this->controllerFinalName, $this->method);
        if (!is_a($controllerInstance, GvController::class)) {
            throw new InvalidControllerException(
                'The controller that you are trying to instantiate should be extending GvController',
                ["controller class" => get_class($controllerInstance)]
            );
        }

        $annotationUtil = $this->diContainer->get('annotationUtil');
        $allowedHttpMethods = $annotationUtil->getAnnotationContentFromMethod(
            get_class($controllerInstance),
            $this->method,
            AnnotationUtil::HTTP_ANNOTATION
        );

        $controllerInstance->init($allowedHttpMethods);
    }

    /**
     * Set the value of controllerAutoloadingNames
     *
     * @return  self
     */
    public function setControllerAutoloadingNames($controllerAutoloadingNames)
    {
        $this->controllerAutoloadingNames = $controllerAutoloadingNames;

        return $this;
    }

    /**
     * Set the value of diContainer
     *
     * @return  self
     */
    public function setDiContainer($diContainer)
    {
        $this->diContainer = $diContainer;

        return $this;
    }

    /**
     * Get the value of controllerFinalName
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerFinalName;
    }

    /**
     * Get the value of method
     * @return string
     */
    public function getMethodName()
    {
        return $this->method;
    }
}
