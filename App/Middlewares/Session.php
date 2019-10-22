<?php

namespace App\Middlewares;

use Novvai\Middlewares\Interfaces\MiddlewareInterface;

class Session implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        session_start();
        return $next();
    }
}
