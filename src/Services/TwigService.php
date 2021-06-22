<?php

namespace Gvera\Services;

use Gvera\Helpers\config\Config;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigService
{
    const VIEWS_PREFIX = __DIR__ . '/../Views/';
    private Config $config;
    private $loadTwig;
    private $twig;
    /**
     * TwigService constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $controllerName
     * @param $controllerMethod
     * @param null $path
     * @return bool
     */
    public function needsTwig($controllerName, $controllerMethod, $path = null): bool
    {
        $path = $path ?? self::VIEWS_PREFIX;
        if (null === $this->loadTwig) {
            $this->loadTwig = file_exists(
                $path .
                $controllerName .
                DIRECTORY_SEPARATOR .
                $controllerMethod . '.html.twig'
            );
        }
        return $this->loadTwig;
    }

    /**
     * @param string|null $path
     * @return Environment
     */
    public function loadTwig(string $path = null): Environment
    {
        $path = $path ?? self::VIEWS_PREFIX;
        $devMode = boolval($this->config->getConfigItem('devmode'));
        $cache = $devMode ? false : __DIR__ . '/../../var/cache/views/';
        $loader = new FilesystemLoader($path);
        $this->twig = new Environment($loader, ['cache' => $cache, 'debug' => $devMode]);
        return $this->twig;
    }

    /**
     * @param $name
     * @param $method
     * @param $viewParams
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render($name, $method, $viewParams): string
    {
        return $this->twig->render(
            DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $method . '.html.twig',
            $viewParams
        );
    }

    public function reset()
    {
        $this->twig = null;
        $this->loadTwig = null;
    }
}
