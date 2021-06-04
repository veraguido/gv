<?php

namespace Gvera\Helpers\bootstrap;

use Gvera\Cache\Cache;
use Gvera\Exceptions\InvalidArgumentException;
use Gvera\Helpers\config\Config;
use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Helpers\dependencyInjection\DIRegistry;
use Gvera\Helpers\events\EventListenerRegistry;
use Gvera\Helpers\http\JSONResponse;
use Gvera\Helpers\http\Response;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\routes\RouteManager;
use Symfony\Component\Yaml\Yaml;
use Throwable;

class Bootstrap
{
    const CONFIG_DEFAULT_FILE_PATH = CONFIG_ROOT . "config.yml";
    const ROUTES_DEFAULT_FILE_PATH = CONFIG_ROOT . "routes.yml";
    private DIContainer $diContainer;
    private Config $config;

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Bootstrap constructor.
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function __construct()
    {
        $this->config = new Config(self::CONFIG_DEFAULT_FILE_PATH);
        Cache::setConfig($this->config);
        $this->diContainer = $this->initializeDIContainer();


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
                file_get_contents(self::ROUTES_DEFAULT_FILE_PATH)
            )['routes'];
            Cache::getCache()->save(RouteManager::ROUTE_CACHE_KEY, $routes);
        } else {
            $routes = Cache::getCache()->load(RouteManager::ROUTE_CACHE_KEY);
        }

        $routeManager = $this->diContainer->get('routeManager');
        $routeManager->setRoutes($routes);

        $eventRegistry = new EventListenerRegistry($this->diContainer);
        $eventRegistry->registerEventListeners();
    }

    /**
     * @return DIContainer
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

    private bool $devMode = false;

    /**
     * @return DIContainer
     * @throws InvalidArgumentException
     */
    private function initializeDIContainer(): DIContainer
    {
        $diContainer = new DIContainer();
        $diRegistry = new DIRegistry($diContainer);
        $diRegistry->registerObjects();
        return $diContainer;
    }

    /**
     * @throws \ReflectionException
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
