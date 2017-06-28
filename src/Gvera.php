<?php namespace Gvera;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Gvera\Cache\RedisCache;
use Gvera\Controllers\GController;
use Gvera\Controllers\HttpCodeResponse;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\routes\RouteManager;
use Symfony\Component\Yaml\Yaml;

class Gvera {

    const CONTROLLERS_PREFIX = 'Gvera\\Controllers\\';
    const MYSQL_CONFIG_KEY = 'mysql_config';
    private $method = 'index';
    private $controllerFinalName;

    public function run()
    {
        $specialRouteApplied = $this->useSpecialRoutesIfApply();

        if (!$specialRouteApplied)
            $this->parseUri();
    }

    private function useSpecialRoutesIfApply()
    {
        $rm = new RouteManager();
        $action = $rm->getRoute($_SERVER['REQUEST_URI'], new HttpRequest());
        echo $action;
    }

    private function parseUri()
    {
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

        $controller = $this->checkIfControllerExists($this->controllerFinalName);
        $this->initializeControllerInstance($controller);
    }

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

    private function getEntityManager() {
        $path = array('/src/Models');

        if (RedisCache::getInstance()->exists('mysql_config')) {
           $config =  unserialize(RedisCache::getInstance()->load(self::MYSQL_CONFIG_KEY));
        } else {
            $config = Yaml::parse(file_get_contents("../config/config.yml"))["config"]["mysql"];
            RedisCache::getInstance()->save(self::MYSQL_CONFIG_KEY, serialize($config));
        }
        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'user'     => $config['username'],
            'password' => $config['password'],
            'dbname'   => $config['db_name']
        );

        $doctrineConfig = Setup::createAnnotationMetadataConfiguration($path, false);
        return EntityManager::create($dbParams, $doctrineConfig);
    }

}

