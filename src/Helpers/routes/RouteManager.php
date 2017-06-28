<?php namespace Gvera\Helpers\routes;


use Gvera\Cache\RedisCache;
use Symfony\Component\Yaml\Yaml;

class RouteManager
{
    private $routes;
    const ROUTE_NEEDLE = ':';
    const ROUTE_CACHE_KEY = 'routes';

    public function __construct()
    {
        if (RedisCache::getInstance()->exists(self::ROUTE_CACHE_KEY)) {
            $this->routes = unserialize(RedisCache::getInstance()->load(self::ROUTE_CACHE_KEY));
        } else {
            $this->routes = Yaml::parse(file_get_contents(__DIR__ . '/../../../config/routes.yml'))['routes'];
            RedisCache::getInstance()->save(self::ROUTE_CACHE_KEY, serialize($this->routes));
        }
    }

    public function getRoute($pathLike, $httpRequest)
    {
        $pathLikeArray = explode("/", $pathLike);

        $filteredRoutes = $this->stripRoutesByHttpMethod($httpRequest->getRequestType());

        foreach ($filteredRoutes as $route) {

            echo $pathLikeArray[1];
            if ( (strpos($route['uri'], $pathLikeArray[1]) !== false) && (strpos($route['uri'], $pathLikeArray[2]) !== false) ) {

                $totalRoute = $route['uri'] ;
                $totalRouteArray = explode("/", $totalRoute);
                $routeController = $totalRouteArray[1];
                $routeMethod = $totalRouteArray[2];

                $urlCheck = ($pathLikeArray[0] == $routeController && $pathLikeArray[1] == $routeMethod);

                $this->convertUriParams($totalRoute, explode('/', $totalRoute), $httpRequest);
                if($urlCheck)
                    return $route['action'];
                else
                    continue;
            }

        }

        return false;
    }

    public function addRoute($method, $route, $action)
    {
        $this->routes[$method][] = array('route' => $route, 'action' => $action);
    }

    private function convertUriParams($totalRoute, $pathLikeArray, $httpRequest)
    {
        print_r(($pathLikeArray));
        for ($i = 0; $i < count($pathLikeArray); $i++) {
            if (substr_count($pathLikeArray[$i], self::ROUTE_NEEDLE) == 2) {
                $httpRequest->setParameter(str_replace(self::ROUTE_NEEDLE, '', $pathLikeArray[$i]), explode('/', $pathLikeArray)[$i]);
            }
        }

        print_r($httpRequest->getParameters());
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