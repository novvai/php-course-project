<?php

namespace App\Middlewares;

use Novvai\Middlewares\Interfaces\MiddlewareInterface;

class Session implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        session_start();

        $this->manageFlashSessions();

        return $next();
    }


    private function manageFlashSessions()
    {
        if(isset($_SESSION["flash"])){
            if($_SESSION["flash"]["_fl"] > 0){
                $_SESSION["flash"]["_fl"]--;
                return null;
            }

            foreach($_SESSION["flash"]["keys"]??[] as $key){
                unset($_SESSION[$key]);
            }
            unset($_SESSION["flash"]);
        }
        
    }
}
