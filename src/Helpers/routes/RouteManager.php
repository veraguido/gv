<?php namespace Gvera\Helpers\routes;


use Gvera\Cache\RedisCache;
use Symfony\Component\Yaml\Yaml;

class RouteManager
{
    private $routes;
    const ROUTE_NEEDLE = ':';
    const ROUTE_CACHE_KEY = 'routes';
    private $httpRequest;

    public function __construct($httpRequest)
    {
        $this->httpRequest = $httpRequest;
        if (RedisCache::getInstance()->exists(self::ROUTE_CACHE_KEY)) {
            $this->routes = unserialize(RedisCache::getInstance()->load(self::ROUTE_CACHE_KEY));
        } else {
            $this->routes = Yaml::parse(file_get_contents(__DIR__ . '/../../../config/routes.yml'))['routes'];
            RedisCache::getInstance()->save(self::ROUTE_CACHE_KEY, serialize($this->routes));
        }
    }

    public function getRoute($pathLike)
    {
        $pathLikeArray = explode("/", $pathLike);
        if ( !isset($pathLikeArray[2]) )
            return false;

        $filteredRoutes = $this->stripRoutesByHttpMethod($this->httpRequest->getRequestType());

        foreach ($filteredRoutes as $route) {

            if ( (strpos($route['uri'], $pathLikeArray[1]) !== false) && (strpos($route['uri'], $pathLikeArray[2]) !== false) ) {

                $totalRoute = $route['uri'] ;
                $totalRouteArray = explode("/", $totalRoute);
                $routeController = $totalRouteArray[1];
                $routeMethod = $totalRouteArray[2];

                $urlCheck = ($pathLikeArray[1] == $routeController && $pathLikeArray[2] == $routeMethod);
                $checkUri = $this->convertUriParams($pathLikeArray, explode('/', $totalRoute));
                if($urlCheck && $checkUri)
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

    private function convertUriParams($totalRoute, $pathLikeArray)
    {
        for ($i = 0; $i < count($pathLikeArray); $i++) {
            if (substr_count($pathLikeArray[$i], self::ROUTE_NEEDLE) == 2) {

                if (empty($totalRoute[$i]))
                    return false;

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