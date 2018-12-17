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


        $routes = Cache::getCache()->load(RouteManager::ROUTE_CACHE_KEY);

        if (null === $routes) {
            $routes = \Symfony\Component\Yaml\Yaml::parse(
                file_get_contents(__DIR__ . '/../../config/routes.yml')
            )['routes'];
        }

        $routeManager = $this->diContainer->get('routeManager');
        $routeManager->setRoutes($routes);

        $eventRegistry = $this->diContainer->get("eventListenerRegistry");
        $eventRegistry->registerEventListeners();
    }

    /**
     * @return \Gvera\Helpers\dependencyInjection\DIContainer
     */
    public function getDiContainer(): \Gvera\Helpers\dependencyInjection\DIContainer
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

    private function initializeDIContainer()
    {
        $diContainer = new \Gvera\Helpers\dependencyInjection\DIContainer();
        $diRegistry = new \Gvera\Helpers\dependencyInjection\DIRegistry($diContainer);
        $diRegistry->registerObjects();
        return $diContainer;
    }

}