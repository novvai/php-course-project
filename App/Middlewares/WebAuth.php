<?php

namespace App\Middlewares;

use Novvai\Middlewares\Interfaces\MiddlewareInterface;
use Novvai\Response\Response;

class WebAuth implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        return (!isset($_SESSION["user_session"])) ? Response::redirect('login') : $next();
    }
}
