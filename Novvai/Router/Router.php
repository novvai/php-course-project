<?php

namespace Novvai\Router;

use Novvai\Router\Exceptions\NotFound;

#singletone Router
class Router
{
    private static $instance = null;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }
    private $routes = [
        "GET" => [],
        "POST" => []
    ];

    private $middlewareBuffer = [];

    private $middlewareGroups = [];

    private function __construct()
    { }


    /**
     * Attempts to find stored route
     * if found returns Class, method that is going to be invoked
     * and the arguments that are supplied in the route, by wildcards
     * 
     * @param string $method
     * @param string $path
     * 
     * @return array
     */
    public function getRequestedRoute(string $method, string $path): array
    {
        $pathComponents = explode("/", $path);
        $pathComponentsCount = count($pathComponents);

        foreach ($this->routes[$method] as $route) {
            $routeComponents = explode("/", $route[0]);
            $routeComponentsCount = count($routeComponents);
            $matched = true;
            $wildCards = [];
            if ($routeComponentsCount == $pathComponentsCount) {
                foreach ($routeComponents as $key => $routeComponent) {
                    if ($pathComponents[$key] != $routeComponent && $routeComponent[0] !== "{") {
                        $matched = false;
                        break;
                    } else if ($routeComponent[0] == "{") {
                        $wildCards[] = $pathComponents[$key];
                    }
                }
                if (!$matched) {
                    continue;
                }
                $middlewareGroups = $this->getMiddlewareGroups($route[0]);
                return [$route[1], $route[2], $wildCards, $middlewareGroups];
            }
        }
        throw new NotFound("Route $path is not registered", 40002);
    }

    /**
     * Validate and add to the corresponding array
     */
    private function registerRoute($type, $route, $executioner)
    {
        list($class, $method) = $this->extractComponents($executioner);
        $this->registerMiddleware($route);
        $this->checkExecutioner($class);
        $this->routes[$type][] = [$route, $class, $method];
    }

    /**
     * Registers a GET route
     */
    public static function get(string $route, string $executioner): void
    {
        $instance = self::getInstance();
        $route = self::prepend_path_slash($route);
        $instance->registerRoute('GET', $route, $executioner);
    }

    /**
     * Registers a POST route
     */
    public static function post(string $route, string $executioner): void
    {
        $instance = self::getInstance();
        $route = self::prepend_path_slash($route);
        $instance->registerRoute('POST', $route, $executioner);
    }

    public static function middlewareGroup($string, callable $fn)
    {
        $instance = self::getInstance();
        $instance->middlewareBuffer[] = $string;
        $fn();
        array_pop($instance->middlewareBuffer);
    }

    /**
     * Prepends slash to the path
     * @return string
     */
    private static function prepend_path_slash(string $path): string
    {
        return $path[0] == '/' ? $path : "/" . $path;
    }

    /**
     * @param string $route
     * 
     * @return array
     */
    private function getMiddlewareGroups(string $route): array
    {
        return (isset($this->middlewareGroups[$route]) ? $this->middlewareGroups[$route] : []);
    }

    /**
     * Registers Middleware group to given route path
     * 
     * @param string $route
     */
    private function registerMiddleware(string $route)
    {
        $this->middlewareGroups = array_merge($this->middlewareGroups, [$route => $this->middlewareBuffer]);
    }
    /**
     * Check if the Executioner Class does exist
     * 
     * @throw Exceptions/NotFound
     */
    private function checkExecutioner(string $className)
    {
        if (!class_exists($className)) {
            throw new NotFound($className, 40001);
        }
    }
    /**
     * Splits the executioner string to Class and Method components
     * 
     * @param string
     * 
     * @return array
     */
    private function extractComponents(string $executioner): array
    {
        return explode('@', $executioner);
    }
}
