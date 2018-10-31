<?php namespace Gvera\Helpers\routes;

use Gvera\Cache\Cache;
use Gvera\Helpers\http\HttpRequest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class RouteManager
 * @package Gvera\Helpers\routes
 * The routing is managed through convention over configuration by default, but a custom route could be added in the
 * routes.yml file, that rule will override and take precedence. This class has the algorithm that decides if the route
 * that is being input match any of the ones that are noted in routes.yml.
 */
class RouteManager
{
    private $routes;
    const ROUTE_NEEDLE = ':';
    const ROUTE_CACHE_KEY = 'gv_routes';
    private $httpRequest;
    private $excludeDirectories = [".", ".."];

    /**
     * @Cached
     * RouteManager constructor.
     * @param $httpRequest
     */
    public function __construct(HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }

    public function setRoutes($routes) {
        $this->routes = $routes;
    }

    /**
     * @return mixed
     */
    public function getRoute($pathLike)
    {
        $pathLikeArray = explode("/", $pathLike);
        if (!$this->isPathLikeArrayValid($pathLikeArray)) {
            return false;
        }

        $filteredRoutes = $this->stripRoutesByHttpMethod($this->httpRequest->getRequestType());

        foreach ($filteredRoutes as $route) {
            if ($routeFound = $this->defineRoute($route, $pathLikeArray)) {
                return $routeFound;
            }
        }
        return false;
    }

    public function addRoute($method, $route, $action)
    {
        $this->routes[$method][] = array('route' => $route, 'action' => $action);
    }

    public function getExcludeDirectories()
    {
        return $this->excludeDirectories;
    }

    /**
     * @return string|bool
     */
    private function defineRoute($route, $pathLikeArray)
    {
        if (!(strpos($route['uri'], $pathLikeArray[1]) !== false) ||
            !(strpos($route['uri'], $pathLikeArray[2]) !== false)) {
                return false;
        }
            $totalRoute = $route['uri'] ;
            $totalRouteArray = explode("/", $totalRoute);
            $routeController = $totalRouteArray[1];
            $routeMethod = $totalRouteArray[2];
            
            return $this->isUrlAndUriValid($pathLikeArray, $routeController, $routeMethod, $totalRoute, $route);
    }

    /**
     * @return string|bool
     */
    private function isUrlAndUriValid($pathLikeArray, $routeController, $routeMethod, $totalRoute, $route)
    {
        $urlCheck = ($pathLikeArray[1] == $routeController && $pathLikeArray[2] == $routeMethod);
        $checkUri = $this->convertUriParams($pathLikeArray, explode('/', $totalRoute));
        return $urlCheck && $checkUri ? $route['action'] : false;
    }

    private function isPathLikeArrayValid($pathLikeArray)
    {
        return isset($pathLikeArray[2]) && !empty($pathLikeArray[2]);
    }

    private function convertUriParams($totalRoute, $pathLikeArray)
    {
        $count = count($pathLikeArray);
        for ($i = 0; $i < $count; $i++) {
            if (substr_count($pathLikeArray[$i], self::ROUTE_NEEDLE) == 2) {
                if (empty($totalRoute[$i])) {
                    return false;
                }

                $this->httpRequest->setParameter(
                    str_replace(self::ROUTE_NEEDLE, '', $pathLikeArray[$i]),
                    $totalRoute[$i]
                );
            }
        }

        return true;
    }

    private function stripRoutesByHttpMethod($method)
    {
        $filteredRoutes = array();
        foreach ($this->routes as $route) {
            if ($route['method'] == $method) {
                $filteredRoutes[] = $route;
            }
        }

        return $filteredRoutes;
    }
}
