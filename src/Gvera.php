<?php namespace Gvera;


use Gvera\Controllers\GController;

class Gvera {

    const CONTROLLERS_PREFIX = 'Gvera\\Controllers\\';
    private $method = 'index';
    private $controllerFinalName;

    public function run() {
        $uriData = @parse_url($_SERVER['REQUEST_URI']);

        if ($uriData === false) {
            // Do something?
        } else {
            if (isset($uriData['path'])) {
                $uriArray = explode("/", $uriData['path']);
            }
        }

        $this->method = $this->getFinalMethodName($uriArray[2]);
        $this->controllerFinalName = $this->getFinalControllerName($uriArray[1]);
        $controller = $this->checkIfControllerExists($this->controllerFinalName);

        $controllerInstance = new $controller($this->controllerFinalName, $this->method);
        if (!is_a($controllerInstance, self::CONTROLLERS_PREFIX . 'GController'))
            throw new \Exception('The controller that you are trying to instantiate should be extending GController');

        $controllerInstance->init();
    }

    private function checkIfControllerExists($controllerName){

        $controllerFullName = self::CONTROLLERS_PREFIX . $controllerName;

        if($controllerName == "GController")
            throw new \Exception('GController is not a valid controller');

        if (!class_exists($controllerFullName)) {
            $controllerFullName = self::CONTROLLERS_PREFIX . GController::HTTP_RESPONSE_CODE_CONTROLLER_NAME;
            $this->controllerFinalName = GController::HTTP_RESPONSE_CODE_CONTROLLER_NAME;
            $this->method = 'resourceNotFound';
        }

        return $controllerFullName;
    }

    private function getFinalControllerName($rawName)
    {
        return ucfirst(strtolower($rawName));
    }

    private function getFinalMethodName($methodName)
    {
        return ($methodName === null || $methodName == '') ? GController::DEFAULT_METHOD :$methodName;
    }

}

