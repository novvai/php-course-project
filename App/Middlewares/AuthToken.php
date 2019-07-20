<?php

namespace App\Middlewares;

use Novvai\Middlewares\Interfaces\MiddlewareInterface;
use Novvai\Response\JsonResponse;

class AuthToken implements MiddlewareInterface{
    public function handle($request, callable $next)
    {
        return $next();
    }
}