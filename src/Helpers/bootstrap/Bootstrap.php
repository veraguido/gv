<?php

namespace Gvera\Helpers\bootstrap;

use Exception;
use Gvera\Cache\Cache;
use Gvera\Cache\FilesCache;
use Gvera\Exceptions\InvalidArgumentException;
use Gvera\Helpers\config\Config;
use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Helpers\dependencyInjection\DIRegistry;
use Gvera\Helpers\events\EventListenerRegistry;
use Gvera\Helpers\http\JSONResponse;
use Gvera\Helpers\http\Response;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\routes\RouteManager;
use ReflectionException;
use Symfony\Component\Yaml\Yaml;
use Throwable;

class Bootstrap
{
    const CONFIG_DEFAULT_FILE_PATH = CONFIG_ROOT . "config.yml";
    const CONFIG_DEFAULT_IOC_PATH = CONFIG_ROOT . "ioc.yml";
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
     * Bootstrap constructor. The order of things in this constructor is important
     * This can be only executed from a webserver as it will bootstrap
     * all that's needed for the requests and responses to be covered
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws Exception
     */
    public function __construct()
    {
        $this->config = new Config(self::CONFIG_DEFAULT_FILE_PATH);
        Locale::setLocalesDirectory(LOCALE_ROOT);
        Cache::setConfig($this->config);
        $this->initializeDIContainer();
        $this->validateThrottling();
        $this->initializeEventListenerRegistry();
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
        return $this->config->getConfigItem('devmode');
    }

    /**
     * @throws InvalidArgumentException
     */
    private function initializeDIContainer()
    {
        $diContainer = new DIContainer();
        $diRegistry = new DIRegistry($diContainer, self::CONFIG_DEFAULT_IOC_PATH);
        $diRegistry->registerObjects();
        $this->diContainer = $diContainer;
    }

    /**
     * @throws ReflectionException
     */
    private function validateThrottling()
    {
        try {
            if ($this->config->getConfigItem('throttling')) {
                if (!isset($_SERVER['REMOTE_ADDR'])) {
                    return;
                }

                if (is_a(Cache::getCache(), FilesCache::class)) {
                    return;
                }

                $throttlingService = $this->diContainer->get('throttlingService');
                $throttlingService->setIp($_SERVER['REMOTE_ADDR']);
                $throttlingService->setAllowedRequestsPerSecond(
                    $this->config->getConfigItem('allowed_requests_per_second')
                );
                $throttlingService->validateRate();
            }
        } catch (Throwable $exception) {
            $this->diContainer->get('logger')->err($exception->getMessage());
            $httpResponse = $this->diContainer->get('httpResponse');
            $content = ['message' => Locale::getLocale('something went wrong')];

            $httpResponse->response(new JSONResponse($content, Response::HTTP_RESPONSE_BAD_REQUEST));
            $httpResponse->terminate();
        }
    }

    /**
     * @throws ReflectionException
     */
    private function initializeEventListenerRegistry()
    {
        $eventRegistry = new GvEventListenerRegistry($this->diContainer);
        $eventRegistry->registerEventListeners();
    }
}
