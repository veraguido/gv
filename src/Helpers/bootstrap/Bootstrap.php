<?php

namespace Gvera\Helpers\bootstrap;

use Gvera\Cache\Cache;
use Gvera\Helpers\config\Config;
use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Helpers\dependencyInjection\DIRegistry;
use Gvera\Helpers\http\JSONResponse;
use Gvera\Helpers\http\Response;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\routes\RouteManager;
use Gvera\Helpers\session\Session;
use Symfony\Component\Yaml\Yaml;
use Throwable;

class Bootstrap
{
    const THROTTLING_LAST_CALL_KEY = 'last_call';
    private $diContainer;
    private $config;

    /**
     * @return \Gvera\Helpers\config\Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Bootstrap constructor.
     * @throws \Gvera\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function __construct()
    {
        $this->diContainer = $this->initializeDIContainer();

        $this->config = $this->diContainer->get('config');

        try {
            if ($this->config->getConfigItem('throttling')) {
                $this->validateThrottling();
            }
        } catch (Throwable $exception) {
            $httpResponse = $this->diContainer->get('httpResponse');
            $content = ['message' => Locale::getLocale('something went wrong')];

            $httpResponse->response(new JSONResponse($content, Response::HTTP_RESPONSE_BAD_REQUEST));
            $httpResponse->terminate();
        }

        if (!Cache::getCache()->exists(RouteManager::ROUTE_CACHE_KEY)) {
            $routes = Yaml::parse(
                file_get_contents(__DIR__ . '/../../../config/routes.yml')
            )['routes'];
            Cache::getCache()->save(RouteManager::ROUTE_CACHE_KEY, $routes);
        } else {
            $routes = Cache::getCache()->load(RouteManager::ROUTE_CACHE_KEY);
        }

        $routeManager = $this->diContainer->get('routeManager');

        $routeManager->setRoutes($routes);

        $eventRegistry = $this->diContainer->get("eventListenerRegistry");
        $eventRegistry->registerEventListeners();
    }

    /**
     * @return \Gvera\Helpers\dependencyInjection\DIContainer
     */
    public function getDiContainer(): DIContainer
    {
        return $this->diContainer;
    }
    /**
     * @return bool
     */
    public function isDevMode(): bool
    {
        return $this->devMode;
    }

    private $devMode = false;

    /**
     * @return DIContainer
     */
    private function initializeDIContainer()
    {
        $diContainer = new DIContainer();
        $diRegistry = new DIRegistry($diContainer);
        $diRegistry->registerObjects();
        $diContainer->initialize();
        return $diContainer;
    }

    /**
     * @param Session $session
     * @throws \Exception
     */
    private function validateThrottling()
    {
        if (!isset($_SERVER['REMOTE_ADDR'])) {
            return;
        }
        
        $throttlingService = $this->diContainer->get('throttlingService');
        $throttlingService->setIp($_SERVER['REMOTE_ADDR']);
        $throttlingService->setAllowedRequestsPerSecond($this->config->getConfigItem('allowed_requests_per_second'));
        $throttlingService->validateRate();
    }
}
