<?php namespace Gvera;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOMySql\Driver;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Gvera\Cache\Cache;
use Gvera\Controllers\GController;
use Gvera\Controllers\HttpCodeResponse;
use Gvera\Helpers\config\Config;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\routes\RouteManager;

class Gvera {

    const CONTROLLERS_PREFIX = 'Gvera\\Controllers\\';
    private $method = 'index';
    private $controllerFinalName;

    /**
     * Application's entry point
     */
    public function run()
    {
        $this->parseUri($this->useSpecialRoutesIfApply());
    }

    /**
     * @return bool
     * This will check on routes.yml if a route is overwritten.
     */
    private function useSpecialRoutesIfApply()
    {
        $rm = new RouteManager(HttpRequest::getInstance());
        return $rm->getRoute($_SERVER['REQUEST_URI']);
    }

    /**
     * @param bool $action
     * If the route was already defined in the routes.yml file then that one will take precedence over the
     * convention over configuration strategy (host.com/Controller/Method)
     */
    private function parseUri($action = false)
    {

        if ($action) {
            $actionArr = explode('->', $action);
            $this->controllerFinalName = $this->getControllerFinalName($actionArr[0]);
            $this->method = $this->getMethodFinalName($actionArr[1]);
        } else {
            $uriData = @parse_url($_SERVER['REQUEST_URI']);

            if ($uriData === false) {
                $this->controllerFinalName =  $this->getControllerFinalName('');
                $this->method = $this->getMethodFinalName('');
            } else {
                if (isset($uriData['path'])) {
                    $uriArray = explode('/', $uriData['path']);
                }


                $methodName = isset($uriArray[2]) ? $uriArray[2] : '';
                $this->method = $this->getMethodFinalName($methodName);
                $this->controllerFinalName = $this->getControllerFinalName($uriArray[1]);
            }
        }

        $controller = $this->checkIfControllerExists($this->controllerFinalName);
        $this->initializeControllerInstance($controller);
    }

    /**
     * @param $controllerName
     * @return string
     * @throws \Exception
     * All controllers should extend from GController. By default if a Controller does not exist
     * it fallbacks to the HttpCodeResponse controller.
     */
    private function checkIfControllerExists($controllerName)
    {

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

    /**
     * @param $rawName
     * @return string
     * If no Controller/Method is specified it will fallback to the default controller (Index controller)
     */
    private function getControllerFinalName($rawName)
    {
        return ($rawName != null && $rawName != "") ? ucfirst(strtolower($rawName)) : GController::DEFAULT_CONTROLLER;
    }

    private function getMethodFinalName($methodName)
    {
        //remove http get params if are present
        $methodName = explode('?', $methodName)[0];

        //if there's no method assigned then return the default method call.
        return ($methodName === null || $methodName == '') ? GController::DEFAULT_METHOD : $methodName;
    }

    private function initializeControllerInstance($controllerFullName)
    {
        $controllerInstance = new $controllerFullName($this->controllerFinalName, $this->method, $this->getEntityManager());
        if (!is_a($controllerInstance, GController::class))
            throw new \Exception('The controller that you are trying to instantiate should be extending GController');

        $controllerInstance->init();
    }

    private function getEntityManager()
    {
        $path = array('src/Models');

        $mysqlConfig = Config::getInstance()->getConfig('mysql');

        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'user'     => $mysqlConfig['username'],
            'password' => $mysqlConfig['password'],
            'dbname'   => $mysqlConfig['db_name']
        );

        $doctrineConfig = Setup::createAnnotationMetadataConfiguration($path, (bool) Config::getInstance()->getConfig('devmode'));
        return EntityManager::create($dbParams, $doctrineConfig);
    }


}

