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
        list($class, $execMethod, $arguments, $middleware_group) = $this->getRequestedRoute();

        echo $this->middleware_instance->process($middleware_group,[$class, $execMethod, $arguments]);
        
        $this->send();
    }


    private function getRequestedRoute()
    {
        $method = isset($_REQUEST['_method'])?$_REQUEST['_method']:$_SERVER['REQUEST_METHOD'];

        $path = explode('?', $_SERVER['REQUEST_URI'])[0];

        return $this->router()->getRequestedRoute($method, $path);
    }

    private function startRequestProcess()
    {
        if(!($this->hasFastCGI = function_exists('fastcgi_finish_request'))){
            ob_start();
            header("Connection: close\r\n"); 
            header('Content-Encoding: none\r\n');
        }
    }
    private function endRequestProcess()
    {
        if($this->hasFastCGI){
            session_write_close(); //close the session
            fastcgi_finish_request();
        }else{
            $size = ob_get_length();
            header("Content-Length: ". $size . "\r\n"); 
            // send info immediately and close connection
            ob_end_flush();
            flush();
        }
    }
    
    private function send()
    {
        $this->endRequestProcess();   
    }
}
