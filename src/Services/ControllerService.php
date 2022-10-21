<?php

namespace Gvera\Services;

use Gvera\Cache\Cache;
use Gvera\Controllers\GvController;
use Gvera\Exceptions\InvalidControllerException;
use Gvera\Exceptions\NotFoundException;
use Gvera\Helpers\annotations\AnnotationUtil;
use Gvera\Helpers\dependencyInjection\DIContainer;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Runner\Exception;

/**
 * @class
 * This class will be in charge of generating the controller's lifecycle
 */
class ControllerService
{
    const CONTROLLERS_PREFIX = 'Gvera\\Controllers\\';
    const CONTROLLERS_KEY = 'gv_controllers';

    private string $method = 'index';
    private string $controllerFinalName;
    
    private ?string $uriData;
    private array $controllerAutoloadingNames;

    private DIContainer $diContainer;

    /**
     * @param $diContainer
     * @param $uriData
     * @throws \Exception
     * @return void
     */
    public function startControllerLifecycle($diContainer, $uriData)
    {
        $this->diContainer = $diContainer;

        if (!$uriData) {
            $this->redirectToDefault($diContainer);
            return;
        }

        $this->uriData = $uriData;
        $this->generateRegularControllerLifecycle();
    }

    /**
     * @param $controllerName
     * @param $methodName
     * @throws \Exception
     * @return void
     */
    public function generateSpecificControllerLifeCycle($controllerName, $methodName): void
    {
        $this->generateControllerLifecycle($controllerName, $methodName);
    }

    /**
     * @param $diContainer
     * @throws \Exception
     * @return void
     */
    public function redirectToDefault($diContainer): void
    {
        $this->diContainer = $diContainer;
        $response = $diContainer->get('httpResponse');
        $response->redirect('/' . GvController::DEFAULT_CONTROLLER);
    }

    /**
     * @throws \Exception
     * @return void
     */
    private function generateRegularControllerLifecycle(): void
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
     * if there are versions (sub-controllers in the controllers directory, get them and return the auto-loading names)
     */
    private function getApiVersions():array
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
    private function generateControllerLifeCycleBasedOnGivenData($uriArray, $apiVersions): void
    {
        //if a version apply, go through that specific path
        $version = array_key_exists($uriArray[1], $apiVersions) ? $uriArray[1] : null;
        $controllerName = $uriArray[1] ?? null;
        $methodName = $uriArray[2] ?? null;

        if (isset($version)) {
            $controllerName = $uriArray[2] ?? null;
            $methodName = $uriArray[3] ?? null;
        }

        //if it doesn't go through the regular path
        $this->generateControllerLifecycle(
            $controllerName ?? GvController::DEFAULT_CONTROLLER,
            $methodName ?? GvController::DEFAULT_METHOD,
            $version
        );
    }

    /**
     * @param $controller
     * @param $method
     * @param null $version
     * @throws \Exception
     */
    private function generateControllerLifecycle($controller, $method, $version = null): void
    {
        $this->controllerFinalName = $this->getControllerFinalName($controller, $version);
        $this->method = $this->getMethodFinalName($method);
        $controller = $this->getValidControllerClassName($this->controllerFinalName, $version);
        $this->initializeControllerInstance($controller);
    }

    /**
     * @param string|null $rawName
     * @param string|null $version
     * @return string
     * If no Controller/Method is specified it will fall back to the default controller (Index controller)
     */
    private function getControllerFinalName(string $rawName = null, ?string $version = null):string
    {
        if (empty($rawName)) {
            return GvController::DEFAULT_CONTROLLER;
        }

        $autoloadedNames = $this->controllerAutoloadingNames;
        if (isset($version)) {
            $autoloadedNames = $this->controllerAutoloadingNames[$version];
        }

        return $this->getAutoloadedControllerName($autoloadedNames, $rawName);
    }

    /**
     * @param $autoloadedArray
     * @param $name
     * @return string
     */
    private function getAutoloadedControllerName($autoloadedArray, $name): string
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
    private function getMethodFinalName($methodName = null): string
    {
        //remove http get params if are present
        $methodName = explode('?', $methodName)[0];

        //if there's no method assigned then return the default method call.
        return ($methodName == '') ? GvController::DEFAULT_METHOD : $methodName;
    }

    /**
     * @param $controllerName
     * @param $version
     * @return string|null
     * @throws InvalidControllerException
     * @throws NotFoundException
     */
    private function getValidControllerClassName($controllerName, $version): ?string
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
     * @param $controllerAutoloadingNames
     * @return  self
     */
    public function setControllerAutoloadingNames($controllerAutoloadingNames): ControllerService
    {
        $this->controllerAutoloadingNames = $controllerAutoloadingNames;

        return $this;
    }

    /**
     * Set the value of diContainer
     *
     * @param $diContainer
     * @return  self
     */
    public function setDiContainer($diContainer): ControllerService
    {
        $this->diContainer = $diContainer;

        return $this;
    }

    /**
     * Get the value of controllerFinalName
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerFinalName;
    }

    /**
     * Get the value of method
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->method;
    }

    /**
     * @param $scanDirectory
     * @return array
     * @throws \Exception
     */
    public function autoloadControllers($scanDirectory):array
    {
        if (Cache::getCache()->exists(self::CONTROLLERS_KEY)) {
            return Cache::getCache()->load(self::CONTROLLERS_KEY);
        }

        $controllersDir = scandir($scanDirectory);
        $loadedControllers = [];
        foreach ($controllersDir as $autoloadingName) {
            $loadedControllers = $this->loadControllers($scanDirectory, $autoloadingName, $loadedControllers);
        }
        Cache::getCache()->save(self::CONTROLLERS_KEY, $loadedControllers);
        return $loadedControllers;
    }

    /**
     * @param $scanDirectory
     * @param $autoloadingName
     * @param $loadedControllers
     * @return mixed|null
     * @throws \ReflectionException
     */
    private function loadControllers($scanDirectory, $autoloadingName, $loadedControllers)
    {
        $routeManager = $this->diContainer->get('routeManager');
        if (in_array($autoloadingName, $routeManager->getExcludeDirectories())) {
            return null;
        }

        if (is_dir($scanDirectory . $autoloadingName)) {
            $autoloadedSubDir = $this->autoloadControllers($scanDirectory . $autoloadingName);
            $loadedControllers[$autoloadingName] = $autoloadedSubDir;
            return $loadedControllers;
        }

        $correctName = str_replace(".php", "", $autoloadingName);
        $loadedControllers[strtolower($correctName)] = $correctName;
        return $loadedControllers;
    }
}
