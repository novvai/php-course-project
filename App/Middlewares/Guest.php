<?php

namespace App\Middlewares;

use Novvai\Middlewares\Interfaces\MiddlewareInterface;
use Novvai\Response\Response;
use Novvai\Session;

class Guest implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        return Session::make()->has("user_session") ? Response::redirect('dashboard') : $next();
    }
}
