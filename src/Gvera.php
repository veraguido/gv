<?php namespace Gvera;


use Gvera\Controllers\Cars;

class Gvera {

    const CONTROLLERS_PREFIX = 'Gvera\\Controllers\\';

    public function run() {
        $uriData = @parse_url($_SERVER['REQUEST_URI']);

        if ($uriData === false) {
            // Do something?
        } else {
            if (isset($uriData['path'])) {
                $uriArray = explode("/", $uriData['path']);
            }
        }

        $method = strtolower($uriArray[2]);
        $controllerFinalName = $this->getFinalControllerName($uriArray[1]);
        $controller = $this->checkIfControllerExists($controllerFinalName);

        $controllerInstance = new $controller($controllerFinalName, $method);
        if (!is_a($controllerInstance, self::CONTROLLERS_PREFIX . 'GController'))
            throw new \Exception('The controller that you are trying to instantiate should be extending GController');

        $controllerInstance->init();
    }

    private function checkIfControllerExists($controllerName){

        $controllerFullName = self::CONTROLLERS_PREFIX . $controllerName;

        if($controllerName == "GController")
            throw new \Exception('GController is not a valid controller');

        if (!class_exists($controllerFullName))
            throw new \Exception('Controller not found');

        return $controllerFullName;
    }

    private function getFinalControllerName($rawName)
    {
        return ucfirst(strtolower($rawName));
    }

}

