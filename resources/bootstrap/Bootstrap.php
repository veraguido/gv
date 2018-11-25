<?php

class Bootstrap
{
    private $diContainer;
    private $config;

    /**
     * @return \Gvera\Helpers\config\Config
     */
    public function getConfig(): \Gvera\Helpers\config\Config
    {
        return $this->config;
    }

    public function __construct()
    {
        $this->diContainer = $this->initializeDIContainer();

        $this->config = $this->diContainer->get('config');
        $this->config->setConfig(\Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__ . "/../../config/config.yml"))["config"]);

        $routeManager = $this->diContainer->get('routeManager');
        $routeManager->setRoutes(
            \Symfony\Component\Yaml\Yaml::parse(
                file_get_contents(__DIR__ . '/../../config/routes.yml')
            )['routes']
        );

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