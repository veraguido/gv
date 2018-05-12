<?php namespace Gvera;

use Gvera\Cache\Cache;
use Gvera\Controllers\GvController;
use Gvera\Controllers\HttpCodeResponse;
use Gvera\Controllers\Index;
use Gvera\Exceptions\InvalidControllerException;
use Gvera\Exceptions\InvalidVersionException;
use Gvera\Helpers\dependencyInjection\DIRegistry;
use Gvera\Helpers\events\EventListenerRegistry;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\routes\RouteManager;
use Gvera\Helpers\http\HttpResponse;
use Monolog\Logger;
use Gvera\Exceptions\GvException;
use Gvera\Helpers\dependencyInjection\DIContainer;

/**
 * Application Class Doc Comment
 *
 * @category Class
 * @package  src
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 *
 */
class Gvera
{

    const CONTROLLERS_PREFIX = 'Gvera\\Controllers\\';
    const GV_CONTROLLERS_KEY = 'gv_controllers';
    private $method = 'index';
    private $controllerFinalName;
    private $controllerAutoloadingNames = [];
    private $routeManager;
    private $diContainer;

    /**
     * Gvera constructor.
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->diContainer = new DIContainer();
        $diRegistry = new DIRegistry($this->diContainer);
        $diRegistry->registerObjects();
        $this->routeManager = new RouteManager($this->diContainer->get('httpRequest'));
        $eventRegistry = $this->diContainer->get("eventListenerRegistry");
        $eventRegistry->registerEventListeners();
    }

    /**
     * @throws \Exception
     * Application's entry point
     */
    public function run()
    {
        $this->controllerAutoloadingNames = $this->autoloadControllers(__DIR__ . '/Controllers/');
        $this->parseUri($this->supportsSpecialRoutesIfApply());
    }

    /**
     * @throws \Exception
     * In case of not dev mode redirect will be done instead of printing an exception.
     */
    public function redirectToDefault()
    {
        $this->controllerFinalName = GvController::DEFAULT_CONTROLLER;
        $this->method = GvController::DEFAULT_METHOD;
        $this->initializeControllerInstance(Index::class);
    }

    /**
     * @param Throwable $exception
     * @param bool $devMode
     * handle exception thrown and decide what to do depending on app state
     */
    public function handleException(\Throwable $exception, bool $devMode)
    {
        if ($devMode) {
            $this->dieWithMessage($exception->getMessage());
        }

        $arguments = is_a($exception, GvException::class) ? $exception->getArguments() : [];
        $this->logMessageWithArguments($exception->getMessage(), $arguments);
    }

    /**
     * @param $message
     * display message in dev mode
     */
    private function dieWithMessage($message)
    {
        die($message);
    }

    /**
     * @param $message
     * @param $argunments
     * log the exception (not dev mode)
     */
    private function logMessageWithArguments($message, $arguments)
    {
        $logger = new Logger('gv');
        $logger->err($message, $arguments);
        $this->redirectToDefault();
    }

    /**
     * @return bool
     * This will check on routes.yml if a route is overwritten.
     */
    private function supportsSpecialRoutesIfApply()
    {
        return $this->routeManager->getRoute($_SERVER['REQUEST_URI']);
    }

    /**
     * @param bool $action
     * @throws \Exception
     * @return mixed
     * If the route was already defined in the routes.yml file then that one will take precedence over the
     * convention over configuration strategy (host.com/Controller/Method)
     */
    private function parseUri($action = false)
    {

        if ($action) {
            $actionArr = explode('->', $action);
            $this->generateControllerLifecycle(
                $actionArr[0],
                $actionArr[1]
            );
            return;
        }

        $uriData = @parse_url($_SERVER['REQUEST_URI']);

        if (!$uriData) {
            $this->redirectToDefault();
            return;
        }

        if (isset($uriData['path'])) {
            $uriArray = explode('/', $uriData['path']);
            $apiVersions = $this->getApiVersions();

            //if a version apply, go through that specific path
            if (!empty($apiVersions) && array_key_exists($uriArray[1], $apiVersions)) {
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
     * @param $controllerName
     * @return string
     * @throws \Exception
     * All controllers should extend from GvController. By default if a Controller does not exist
     * it fallbacks to the HttpCodeResponse controller.
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

        $this->diContainer->get('httpResponse')->asJson();
        $this->diContainer->get('httpResponse')->notFound();
        $this->diContainer->get('httpResponse')->printError(404, "Resource not found");
        exit();
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
     * @param $rawName
     * @return string
     * If no Controller/Method is specified it will fallback to the default controller (Index controller)
     * @throws InvalidVersionException
     */
    private function getControllerFinalName(string $rawName = null, $version)
    {
        if (empty($rawName)) {
            return GvController::DEFAULT_CONTROLLER;
        }

        if (isset($version)) {
            if (!isset($this->controllerAutoloadingNames[$version])) {
                throw new InvalidVersionException("the version does not exist");
            }

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

        $controllerInstance->init();
    }

    /**
     * @return array
     * @Cached
     * In order to bypass the error of trying to load a class with case insensitive (depending on the OS)
     * The method will check for all the files created under the controllers directory and generate a map of them
     * to be used for the instantiation.
     */
    private function autoloadControllers($scanDirectory)
    {
        if (Cache::getCache()->exists(self::GV_CONTROLLERS_KEY)) {
            return unserialize(Cache::getCache()->load(self::GV_CONTROLLERS_KEY));
        }
        
        $controllersDir = scandir($scanDirectory);
        $autoloadedControllers = [];
        foreach ($controllersDir as $index => $autoloadingName) {
            if (in_array($autoloadingName, $this->routeManager->getExcludeDirectories())) {
                continue;
            }

            if (is_dir($scanDirectory . $autoloadingName)) {
                $autoloadedSubDir = $this->autoloadControllers($scanDirectory . $autoloadingName);
                $autoloadedControllers[$autoloadingName] = $autoloadedSubDir;
                continue;
            }

            $correctName = str_replace(".php", "", $autoloadingName);
            $autoloadedControllers[strtolower($correctName)] = $correctName;
        }
        Cache::getCache()->save(self::GV_CONTROLLERS_KEY, serialize($autoloadedControllers));

        return $autoloadedControllers;
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
}
