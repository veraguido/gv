<?php namespace Gvera;


use Gvera\Controllers\GController;
use Gvera\Controllers\HttpCodeResponse;

class Gvera {

    const CONTROLLERS_PREFIX = 'Gvera\\Controllers\\';
    private $method = 'index';
    private $controllerFinalName;

    public function run() {
        $uriData = @parse_url($_SERVER['REQUEST_URI']);

        if ($uriData === false) {
            $this->controllerFinalName =  $this->getControllerFinalName("");
            $this->method = $this->getMethodFinalName("");
        } else {
            if (isset($uriData['path'])) {
                $uriArray = explode("/", $uriData['path']);
            }
            $this->method = $this->getMethodFinalName($uriArray[2]);
            $this->controllerFinalName = $this->getControllerFinalName($uriArray[1]);
        }

        $controller = $this->checkIfControllerExists($this->controllerFinalName);
        $this->initializeControllerInstance($controller);
    }

    private function checkIfControllerExists($controllerName){

        $controllerFullName = self::CONTROLLERS_PREFIX . $controllerName;

        if($controllerName == "GController")
            throw new \Exception('GController is not a valid controller');

        if (!class_exists($controllerFullName)) {
            $controllerFullName = HttpCodeResponse::class;
            $this->controllerFinalName = $controllerName;
            $this->method = 'resourceNotFound';
        }

        return $controllerFullName;
    }

    private function getControllerFinalName($rawName)
    {
        return ($rawName != null && $rawName != "") ? ucfirst(strtolower($rawName)) : GController::DEFAULT_CONTROLLER;
    }

    private function getMethodFinalName($methodName)
    {
        return ($methodName === null || $methodName == "") ? GController::DEFAULT_METHOD : $methodName;
    }

    private function initializeControllerInstance($controllerFullName)
    {
        $controllerInstance = new $controllerFullName($this->controllerFinalName, $this->method);
        if (!is_a($controllerInstance, GController::class))
            throw new \Exception('The controller that you are trying to instantiate should be extending GController');

        $controllerInstance->init();
    }

}

