<?php namespace Gvera;

use Exception;
use Gvera\Cache\Cache;
use Gvera\Events\ThrowableFiredEvent;
use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Helpers\events\EventDispatcher;
use Gvera\Helpers\routes\RouteManager;
use Gvera\Services\ControllerService;
use ReflectionException;
use Throwable;

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
class Gv
{

    const GV_CONTROLLERS_KEY = 'gv_controllers';
    private array $controllerAutoloadingNames = [];
    private RouteManager $routeManager;
    private DIContainer $diContainer;

    public function __construct(DIContainer $diContainer)
    {
        $this->diContainer = $diContainer;
    }


    private ControllerService $controllerService;

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws ReflectionException
     * @throws Exception
     */
    public function run()
    {
        $this->initializeApp();

        $this->controllerAutoloadingNames = $this->autoloadControllers(__DIR__ . '/Controllers/');
        $this->controllerService->setControllerAutoloadingNames($this->controllerAutoloadingNames);
        $this->parseUri($this->supportsSpecialRoutesIfApply());
    }

    /**
     * @param Throwable $e
     * @param $isDevMode
     * @throws Exception
     */
    public function handleThrowable(Throwable $e, $isDevMode)
    {
        EventDispatcher::dispatchEvent(
            ThrowableFiredEvent::THROWABLE_FIRED_EVENT,
            new ThrowableFiredEvent(
                $e,
                $isDevMode,
                $this->diContainer->get('httpResponse')
            )
        );
    }

    /**
     * @throws Exception
     * In case of not dev mode redirect will be done instead of printing an exception.
     */
    public function redirectToDefault()
    {
        $this->controllerService->redirectToDefault($this->diContainer);
    }

    /**
     * @throws ReflectionException
     */
    private function initializeApp()
    {
        $this->routeManager = $this->diContainer->get("routeManager");

        $this->controllerService = $this->diContainer->get('controllerService');
        $this->controllerService->setDiContainer($this->diContainer);
    }


    /**
     * @return string|boolean
     * This will check on routes.yml if a route is overwritten.
     */
    private function supportsSpecialRoutesIfApply()
    {
        return $this->routeManager->getRoute($_SERVER['REQUEST_URI']);
    }

    /**
     * @param bool|string $action
     * @throws Exception
     * @return mixed
     * If the route was already defined in the routes.yml file then that one will take precedence over the
     * convention over configuration strategy (host.com/Controller/Method)
     */
    private function parseUri($action = false) :void
    {

        $appliedAction = $this->supportsActionIfApplies($action);

        if (true === $appliedAction) {
            return;
        }

        $uriData = strtok($_SERVER['REQUEST_URI'], '?');

        if (!$uriData) {
            $this->redirectToDefault();
            return;
        }

        $this->controllerService->startControllerLifecycle(
            $this->diContainer,
            $uriData
        );
    }

    /**
     * @param $action
     * @return bool
     * @throws Exception
     */
    private function supportsActionIfApplies($action):bool
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
     * @param $scanDirectory
     * @return array
     * @throws Exceptions\InvalidArgumentException
     * In order to bypass the error of trying to load a class with case insensitive (depending on the OS)
     * The method will check for all the files created under the controllers directory and generate a map of them
     * to be used for the instantiation.
     */
    private function autoloadControllers($scanDirectory):array
    {
        if (Cache::getCache()->exists(self::GV_CONTROLLERS_KEY)) {
            return Cache::getCache()->load(self::GV_CONTROLLERS_KEY);
        }

        $controllersDir = scandir($scanDirectory);
        $loadedControllers = [];
        foreach ($controllersDir as $autoloadingName) {
            $loadedControllers = $this->loadControllers($scanDirectory, $autoloadingName, $loadedControllers);
        }
        Cache::getCache()->save(self::GV_CONTROLLERS_KEY, $loadedControllers);
        return $loadedControllers;
    }

    /**
     * @param $scanDirectory
     * @param $autoloadingName
     * @param $loadedControllers
     * @return null
     * @throws Exceptions\InvalidArgumentException
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
