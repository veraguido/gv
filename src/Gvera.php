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
        $this->controllerFinalName = $this->getControllerFinalName(null);
        $this->method = 'index';
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
     * All controllers should extend from GvController. By default if a Controller does not exist
     * it fallbacks to the HttpCodeResponse controller.
     */
    private function checkIfControllerExists($controllerName)
    {
        $controllerFullName = self::CONTROLLERS_PREFIX . $controllerName;

        if ($controllerName == "GvController") {
            throw new \Exception('GvController is not a valid controller');
        }

        if (!class_exists($controllerFullName)) {
            $controllerFullName = HttpCodeResponse::class;
            $this->controllerFinalName = GvController::HTTP_CODE_REPONSE_CONTROLLER_NAME;
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
        if ($rawName != null &&
            $rawName != "" &&
            array_key_exists(strtolower($rawName), $this->controllerAutoloadingNames)) {
            $name = $this->controllerAutoloadingNames[strtolower($rawName)];
        } else {
            $name = GvController::DEFAULT_CONTROLLER;
        }

        return $name;
    }

    private function getMethodFinalName($methodName)
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
