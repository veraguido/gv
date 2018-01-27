<?php namespace Gvera;

use Gvera\Cache\Cache;
use Gvera\Controllers\GvController;
use Gvera\Controllers\HttpCodeResponse;
use Gvera\Controllers\Index;
use Gvera\Helpers\events\EventListenerRegistry;
use Gvera\Helpers\http\HttpRequest;
use Gvera\Helpers\routes\RouteManager;

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
    const RESOURCE_NOT_FOUND_METHOD = 'resourceNotFound';
    private $method = 'index';
    private $controllerFinalName;
    private $controllerAutoloadingNames = [];

    /**
     * @throws \Exception
     * Application's entry point
     */
    public function run()
    {
        EventListenerRegistry::registerEventListeners();
        $this->autoloadControllers();
        $this->parseUri($this->useSpecialRoutesIfApply());
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
     * @throws \Exception
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
            $methodName = isset($uriArray[2]) ? $uriArray[2] : GvController::DEFAULT_METHOD;
            $this->generateControllerLifecycle(
                $uriArray[1],
                $methodName
            );
        }
    }

    /**
     * @param $controller
     * @param $method
     * @throws \Exception
     */
    private function generateControllerLifecycle($controller, $method)
    {
        $this->controllerFinalName = $this->getControllerFinalName($controller);
        $this->method = $this->getMethodFinalName($method);
        $controller = $this->getValidControllerClassName($this->controllerFinalName);
        $this->initializeControllerInstance($controller);
    }

    /**
     * @param $controllerName
     * @return string
     * @throws \Exception
     * All controllers should extend from GvController. By default if a Controller does not exist
     * it fallbacks to the HttpCodeResponse controller.
     */
    private function getValidControllerClassName($controllerName)
    {

        if ($controllerName == "GvController") {
            throw new \Exception('GvController is not a valid controller');
        }

        if (class_exists(self::CONTROLLERS_PREFIX . $controllerName)) {
            return self::CONTROLLERS_PREFIX . $controllerName;
        }

        $this->controllerFinalName = GvController::HTTP_CODE_REPONSE_CONTROLLER_NAME;
        $this->method = self::RESOURCE_NOT_FOUND_METHOD;
        return HttpCodeResponse::class;
    }

    /**
     * @param $rawName
     * @return string
     * If no Controller/Method is specified it will fallback to the default controller (Index controller)
     */
    private function getControllerFinalName(string $rawName)
    {
        if (empty($rawName)) {
            return GvController::DEFAULT_CONTROLLER;
        }

        if (!array_key_exists(strtolower($rawName), $this->controllerAutoloadingNames)) {
            return $rawName;
        }

        return $this->controllerAutoloadingNames[strtolower($rawName)];
    }

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
        $controllerInstance = new $controllerFullName($this->controllerFinalName, $this->method);
        if (!is_a($controllerInstance, GvController::class)) {
            throw new \Exception('The controller that you are trying to instantiate should be extending GvController');
        }

        $controllerInstance->init();
    }

    /**
     * @Cached
     * In order to bypass the error of trying to load a class with case insensitive (depending on the OS)
     * The method will check for all the files created under the controllers directory and generate a map of them
     * to be used for the instantiation.
     */
    private function autoloadControllers()
    {
        if (!Cache::getCache()->exists(self::GV_CONTROLLERS_KEY)) {
            $controllersDir = scandir(__DIR__ . '/Controllers/');
            foreach ($controllersDir as $index => $autoloadingName) {
                $correctName = str_replace(".php", "", $autoloadingName);
                $this->controllerAutoloadingNames[strtolower($correctName)] = $correctName;
                Cache::getCache()->save(self::CONTROLLERS_PREFIX, serialize($this->controllerAutoloadingNames));
            }
        } else {
            $this->controllerAutoloadingNames = Cache::getCache()->load(self::GV_CONTROLLERS_KEY);
        }
    }
}
