<?php

namespace App;

use Novvai\Container;
use Novvai\Router\Router;
use Novvai\Request\Request;
use Novvai\Middlewares\MiddlewareManager;
use App\Http\Controllers\Base as Controller;

class Kernel
{
    private $base_path;
    private $router_instance;
    private $request_instance;
    private $middleware_instance;

    public function  __construct()
    {
        $this->base_path = base_path();
        $this->router_instance = Router::getInstance();
        $this->request_instance = Request::getInstance();
        $this->middleware_instance = MiddlewareManager::getInstance();
    }

    public function router()
    {
        return $this->router_instance;
    }

    public function request()
    {
        return $this->request_instance;
    }

    public function execute()
    {
        list($class, $execMethod, $arguments, $middlewareGroups) = $this->getRequestedRoute();

        $executioner = self::makeExecutioner($class);


        return $this->middleware_instance->process('auth',[$class, $execMethod, $arguments]);


        if (method_exists($executioner, $execMethod)) {
            return call_user_func_array([$executioner, $execMethod], $arguments);
        }
    }


    private function getRequestedRoute()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];

        return $this->router()->getRequestedRoute($method, $path);
    }

    private static function makeExecutioner(string $className): Controller
    {
        return Container::make($className);
    }
}
