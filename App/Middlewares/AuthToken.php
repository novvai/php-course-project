<?php

namespace App\Middlewares;

use Novvai\Middlewares\Interfaces\MiddlewareInterface;

class AuthToken implements MiddlewareInterface{
    public function handle($request, callable $next)
    {
        

        return $next();
    }
}