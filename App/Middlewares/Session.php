<?php

namespace App\Middlewares;

use Novvai\Middlewares\Interfaces\MiddlewareInterface;
use Novvai\Session as WebSession;

class Session implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        WebSession::make();
        return $next();
    }
}
