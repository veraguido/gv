<?php namespace Gvera\Helpers\routes;

class RouteManager
{
    private $routes;

    public function __construct()
    {
        include_once ('/public/routes.php');
    }



    public function getRoute($pathLike)
    {
        $pathLikeArray = explode("/", $pathLike);

        foreach ($this->routes as $route => $action) {
            $routeLike = explode(" ", $route);
            $totalRoute = $routeLike[1];
            $route = explode("/", $totalRoute);
            $routeController = $route[0];
            $routeMethod = $route[1];
            return ($pathLikeArray[0] == $routeController && $pathLikeArray[1] == $routeMethod) ? $action : false;
        }
    }

    public function addRoute($route, $methodCall)
    {
        $this->routes[$route] = $methodCall;
    }
}