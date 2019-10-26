<?php

namespace App\Middlewares;

use Novvai\Middlewares\Interfaces\MiddlewareInterface;

class Guest implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        if(isset($_SESSION["user_session"])){
            return header('location: /dashboard');
        }
        return $next();
    }
}
