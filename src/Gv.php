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

    private RouteManager $routeManager;
    private DIContainer $diContainer;
    private ControllerService $controllerService;

    public function __construct(DIContainer $diContainer)
    {
        $this->diContainer = $diContainer;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function run()
    {
        $this->initializeApp();

        $controllerAutoloadingNames = $this->controllerService
            ->autoloadControllers(__DIR__ . '/Controllers/');
        $this->controllerService->setControllerAutoloadingNames($controllerAutoloadingNames);
        $this->initializeController($this->routeManager->getRoute($_SERVER['REQUEST_URI']));
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
     * @throws ReflectionException
     */
    private function initializeApp()
    {
        $this->routeManager = $this->diContainer->get("routeManager");
        $this->controllerService = $this->diContainer->get('controllerService');
        $this->controllerService->setDiContainer($this->diContainer);
    }

    /**
     * @param bool|string $action
     * @return void
     * If the route was already defined in the routes.yml file then that one will take precedence over the
     * convention over configuration strategy (host.com/Controller/Method)
     * @throws Exception
     */
    private function initializeController(bool|string $action):void
    {
        if ($this->isRouteOverwritten($action)) {
            return;
        }

        $uriData = strtok($_SERVER['REQUEST_URI'], '?');

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
    private function isRouteOverwritten($action):bool
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
}
