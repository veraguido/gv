<?php

namespace Gvera\Helpers\bootstrap;

use Gvera\Cache\Cache;
use Gvera\Helpers\config\Config;
use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Helpers\routes\RouteManager;

class Bootstrap
{
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
     */
    public function __construct()
    {
        $this->diContainer = $this->initializeDIContainer();

        $this->config = $this->diContainer->get('config');


        if (!Cache::getCache()->exists(RouteManager::ROUTE_CACHE_KEY)) {
            $routes = \Symfony\Component\Yaml\Yaml::parse(
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
        $diRegistry = new \Gvera\Helpers\dependencyInjection\DIRegistry($diContainer);
        $diRegistry->registerObjects();
        $diContainer->initialize();
        return $diContainer;
    }
}
