<?php namespace Gvera;

use Gvera\Cache\Cache;
use Gvera\Controllers\GvController;
use Gvera\Controllers\HttpCodeResponse;
use Gvera\Controllers\Index;
use Gvera\Events\ThrowableFiredEvent;
use Gvera\Exceptions\InvalidControllerException;
use Gvera\Exceptions\InvalidVersionException;
use Gvera\Helpers\dependencyInjection\DIRegistry;
use Gvera\Helpers\events\EventDispatcher;
use Gvera\Helpers\events\EventListenerRegistry;
use Gvera\Helpers\http\HttpResponse;
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
    private $controllerAutoloadingNames = [];
    private $routeManager;
    private $diContainer;

    private $controllerService;

    /**
     * @throws \Exception
     * Application's entry point
     */
    public function run($isDevMode)
    {
        $this->initializeApp($isDevMode);

        $this->controllerAutoloadingNames = $this->autoloadControllers(__DIR__ . '/Controllers/');
        $this->controllerService->setControllerAutoloadingNames($this->controllerAutoloadingNames);
        $this->parseUri($this->supportsSpecialRoutesIfApply());
    }

    /**
     * @param \Throwable $e
     * @param $isDevMode
     * @throws \Exception
     */
    public function handleThrowable(\Throwable $e, $isDevMode)
    {
        EventDispatcher::dispatchEvent(
            ThrowableFiredEvent::THROWABLE_FIRED_EVENT,
            new ThrowableFiredEvent(
                $e,
                $isDevMode,
                $this->diContainer->get('httpResponse')
            )
        );

        $this->redirectToDefault();
    }

    /**
     * @throws \Exception
     * In case of not dev mode redirect will be done instead of printing an exception.
     */
    public function redirectToDefault()
    {
        $this->controllerService->redirectToDefault($this->diContainer);
    }

    /**
     * @param $isDevMode
     */
    private function initializeApp($isDevMode)
    {
        //Needed try catch for when typos are added to ioc.yml
        //In that case the eventListenerRegistry is not registered
        //and Throwable cannot be handled.
        try {
            $this->initializeDependencyInjection();
            $eventRegistry = $this->diContainer->get("eventListenerRegistry");
            $eventRegistry->registerEventListeners();
        } catch (\Throwable $t) {
            if (true === $isDevMode) {
                die($t);
            }
        }

        $this->routeManager = $this->diContainer->get("routeManager");

        $this->controllerService = $this->diContainer->get('controllerService');
        $this->controllerService->setDiContainer($this->diContainer);
    }

    private function initializeDependencyInjection()
    {
        $this->diContainer = new DIContainer();
        $diRegistry = new DIRegistry($this->diContainer);
        $diRegistry->registerObjects();
    }

    /**
     * @return string|boolean
     * This will check on routes.yml if a route is overwritten.
     */
    private function supportsSpecialRoutesIfApply()
    {
        return $this->routeManager->getRoute(filter_input(INPUT_SERVER, 'REQUEST_URI'));
    }

    /**
     * @param bool|string $action
     * @throws \Exception
     * @return mixed
     * If the route was already defined in the routes.yml file then that one will take precedence over the
     * convention over configuration strategy (host.com/Controller/Method)
     */
    private function parseUri($action = false)
    {

        $appliedAction = $this->supportsActionIfApplies($action);

        if (true === $appliedAction) {
            return;
        }

        $uriData = @parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI'));

        if (!$uriData) {
            $this->redirectToDefault();
            return;
        }

        $this->controllerService->startControllerLifecyle(
            $this->diContainer,
            $uriData
        );
    }

    /**
     * @return bool
     */
    private function supportsActionIfApplies($action)
    {
        if (!$action) {
            return false;
        }

        $actionArr = explode('->', $action);
        $this->controllerService->generateSpecificControllerLifecycle(
            $actionArr[0],
            $actionArr[1]
        );

        return true;
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
        $loadedControllers = [];
        foreach ($controllersDir as $index => $autoloadingName) {
            $loadedControllers = $this->loadControllers($scanDirectory, $autoloadingName, $loadedControllers);
        }
        Cache::getCache()->save(self::GV_CONTROLLERS_KEY, serialize($loadedControllers));

        return $loadedControllers;
    }

    /**
     * @return null|array<*,array>
     */
    private function loadControllers($scanDirectory, $autoloadingName, $loadedControllers)
    {
        if (in_array($autoloadingName, $this->routeManager->getExcludeDirectories())) {
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
