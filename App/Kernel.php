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
    private $hasFastCGI = true;
    private $router_instance;
    private $request_instance;
    private $middleware_instance;

    public function  __construct()
    {
        $this->base_path = base_path();
        $this->router_instance = Router::getInstance();
        $this->request_instance = Request::getInstance();
        $this->middleware_instance = MiddlewareManager::getInstance();
        $this->startRequestProcess();
    }

    /**
     * Global router accessor
     */
    public function router(): Router
    {
        return $this->router_instance;
    }

    /**
     * Global request accessor
     */
    public function request(): Request
    {
        return $this->request_instance;
    }

    /**
     * Bootstrap and process the requested route 
     */
    public function execute(): void
    {
        list($class, $execMethod, $arguments, $middleware_group) = $this->getRequestedRoute();

        echo $this->middleware_instance->process($middleware_group, [$class, $execMethod, $arguments]);

        $this->send();
    }

    /**
     * Get requested route essential parametes 
     * for the request processing
     * 
     * @return array
     */
    private function getRequestedRoute(): array
    {
        $path   = $this->request()->getUri();
        $method = $this->request()->getMethod();

        return $this->router()->getRequestedRoute($method, $path);
    }

    /**
     * Starts request processing with the correct type function
     * depending on the server configuration
     */
    private function startRequestProcess(): void
    {
        if (!($this->hasFastCGI = function_exists('fastcgi_finish_request'))) {
            ob_start();
            header("Connection: close\r\n");
            header('Content-Encoding: none\r\n');
        }
    }

    /**
     * Ends the request processing
     * continues the execution of background processes if there are any
     */
    private function endRequestProcess(): void
    {
        if ($this->hasFastCGI) {
            session_write_close(); //close the session
            fastcgi_finish_request();
        } else {
            $size = ob_get_length();
            header("Content-Length: " . $size . "\r\n");
            // send info immediately and close connection
            ob_end_flush();
            flush();
        }
    }

    /**
     * Send back the request to the client
     */
    private function send(): void
    {
        $this->endRequestProcess();
    }
}
